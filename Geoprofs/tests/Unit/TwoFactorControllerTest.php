<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TwoFactorControllerTest extends TestCase
{
    public function test_store_2fa_code_generates_and_stores_code()
    {
        // Trigger the store code endpoint
        $response = $this->postJson('/api/store-2fa-code');

        // Assert response structure and status
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'code'])
            ->assertJson(['status' => 'success', 'message' => '2FA code generated successfully']);

        // Assert that the code is stored in the cache
        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_get_2fa_code_generates_new_code_if_none_exists()
    {
        // Ensure no code exists in the cache
        Cache::forget('2fa_code');

        // Trigger the get code endpoint
        $response = $this->getJson('/api/get-2fa-code');

        // Assert response structure and status
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'code'])
            ->assertJson(['status' => 'success']);

        // Assert that a code is now stored in the cache
        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_get_2fa_code_returns_existing_code_if_exists()
    {
        // Manually store a code in the cache
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        // Trigger the get code endpoint
        $response = $this->getJson('/api/get-2fa-code');

        // Assert response contains the existing code
        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'code' => $code]);
    }

    public function test_verify_2fa_code_success()
    {
        // Manually store a code in the cache
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        // Send the correct 2FA code for verification
        $response = $this->postJson('/verify-2fa', ['2fa_code' => $code]);

        // Assert response status and message
        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => '2FA verification successful.']);

        // Assert the code is removed from the cache
        $this->assertNull(Cache::get('2fa_code'));
    }

    public function test_verify_2fa_code_fails_with_incorrect_code()
    {
        // Manually store a code in the cache
        $correctCode = random_int(100000, 999999);
        Cache::put('2fa_code', $correctCode, now()->addMinutes(10));

        // Send an incorrect code
        $response = $this->postJson('/verify-2fa', ['2fa_code' => '123456']);

        // Assert response status and error message
        $response->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'The 2FA code is incorrect or has expired.']);

        // Ensure the correct code is still in the cache
        $this->assertEquals($correctCode, Cache::get('2fa_code'));
    }

    public function test_verify_2fa_code_fails_when_code_not_in_cache()
    {
        // Ensure no code exists in the cache
        Cache::forget('2fa_code');

        // Send a code for verification
        $response = $this->postJson('/verify-2fa', ['2fa_code' => '123456']);

        // Assert response status and error message
        $response->assertStatus(400)
            ->assertJson(['status' => 'error', 'message' => 'The 2FA code is incorrect or has expired.']);

        // Ensure the cache is still empty
        $this->assertNull(Cache::get('2fa_code'));
    }
}