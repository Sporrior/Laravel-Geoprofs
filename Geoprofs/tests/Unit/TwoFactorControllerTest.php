<?php

namespace Tests\Feature;

use App\Models\UserInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_2fa_code()
    {
        // Create a user and authenticate
        $user = UserInfo::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('2fa.store'));

        // Assert the response contains the success message and the code
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'code'])
            ->assertJson(['status' => 'success', 'message' => '2FA code generated successfully']);

        // Assert the code is stored in the cache
        $this->assertNotNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_successfully()
    {
        // Create a user and authenticate
        $user = UserInfo::factory()->create();
        $this->actingAs($user);

        // Generate a 2FA code and store it in the cache
        $code = random_int(100000, 999999);
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        // Make a POST request to verify the 2FA code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => $code]);

        // Assert the user is redirected to the dashboard
        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas('success', '2FA verification successful!');

        // Assert the code is removed from the cache
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_fails_with_incorrect_code()
    {
        // Create a user and authenticate
        $user = UserInfo::factory()->create();
        $this->actingAs($user);

        // Generate a correct 2FA code and store it in the cache
        $correctCode = random_int(100000, 999999);
        Cache::put('2fa_code_' . $user->id, $correctCode, now()->addMinutes(10));

        // Attempt to verify with an incorrect code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => '123456']);

        // Assert the user is redirected back with an error
        $response->assertRedirect()
            ->assertSessionHasErrors(['2fa_code']);

        // Assert the correct code is still in the cache
        $this->assertEquals($correctCode, Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_fails_with_no_code_in_cache()
    {
        // Create a user and authenticate
        $user = UserInfo::factory()->create();
        $this->actingAs($user);

        // Ensure there is no code in the cache
        Cache::forget('2fa_code_' . $user->id);

        // Attempt to verify a code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => '123456']);

        // Assert the user is redirected back with an error
        $response->assertRedirect()
            ->assertSessionHasErrors(['2fa_code']);

        // Assert the cache is still empty
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }
}