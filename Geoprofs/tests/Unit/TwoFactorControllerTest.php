<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TwoFactorControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ]);
    }

    public function test_store_2fa_code_generates_and_stores_code()
    {
        $response = $this->postJson('/api/store-2fa-code');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'code'])
            ->assertJson(['status' => 'success', 'message' => '2FA code generated successfully']);

        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_get_2fa_code_generates_new_code_if_none_exists()
    {
        Cache::forget('2fa_code');

        $response = $this->getJson('/api/get-2fa-code');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'code'])
            ->assertJson(['status' => 'success']);

        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_get_2fa_code_returns_existing_code_if_exists()
    {
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        $response = $this->getJson('/api/get-2fa-code');

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'code' => $code]);
    }

    public function test_verify_2fa_code_success()
    {
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        $response = $this->postJson('/verify-2fa', ['2fa_code' => $code]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => '2FA verification successful.']);

        $this->assertNull(Cache::get('2fa_code'));
    }

    public function test_verify_2fa_code_fails_with_incorrect_code()
    {
        $correctCode = random_int(100000, 999999);
        Cache::put('2fa_code', $correctCode, now()->addMinutes(10));

        $response = $this->postJson('/verify-2fa', ['2fa_code' => '123456']);

        $response->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'De 2FA code is fout. Je hebt nog 2 pogingen over.']);

        $this->assertEquals($correctCode, Cache::get('2fa_code'));
    }

    public function test_verify_2fa_code_fails_when_code_not_in_cache()
    {
        Cache::forget('2fa_code');

        $response = $this->postJson('/verify-2fa', ['2fa_code' => '123456']);

        $response->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'De 2FA code is fout. Je hebt nog 2 pogingen over.']);

        $this->assertNull(Cache::get('2fa_code'));
    }

    public function test_cooldown_activates_after_three_failed_attempts()
    {
        $correctCode = random_int(100000, 999999);
        Cache::put('2fa_code', $correctCode, now()->addMinutes(10));

        $response1 = $this->postJson('/verify-2fa', ['2fa_code' => '111111']);
        $response1->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'De 2FA code is fout. Je hebt nog 2 pogingen over.']);

        $response2 = $this->postJson('/verify-2fa', ['2fa_code' => '222222']);
        $response2->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'De 2FA code is fout. Je hebt nog 1 pogingen over.']);

        $response3 = $this->postJson('/verify-2fa', ['2fa_code' => '333333']);
        $response3->assertStatus(429)
            ->assertJson(['status' => 'error', 'message' => 'Te veel pogingen. Wacht alsjeblieft 5 minuten voor je het weer probeert']);

        $this->assertTrue(Cache::has('2fa_cooldown'));
        $this->assertNull(Cache::get('2fa_attempts'));
    }

    public function test_cooldown_prevents_attempts_during_active_period()
    {
        Cache::put('2fa_cooldown', time() + 300, now()->addMinutes(5));

        $response = $this->postJson('/verify-2fa', ['2fa_code' => '123456']);

        $response->assertStatus(429)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson(['status' => 'error']);

        $message = $response->json()['message'];
        $this->assertStringContainsString('Je hebt een cooldown', $message);
    }

    public function test_successful_verification_resets_attempts_and_cooldown()
    {
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));
        Cache::put('2fa_attempts', 2, now()->addMinutes(10));
        Cache::put('2fa_cooldown', time() + 300, now()->addMinutes(5));

        $response = $this->postJson('/verify-2fa', ['2fa_code' => $code]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => '2FA verification successful.']);

        $this->assertNull(Cache::get('2fa_code'));
        $this->assertNull(Cache::get('2fa_attempts'));
        $this->assertNull(Cache::get('2fa_cooldown'));
    }
}