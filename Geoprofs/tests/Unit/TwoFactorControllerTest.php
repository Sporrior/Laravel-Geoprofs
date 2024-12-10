<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TwoFactorControllerTest extends TestCase
{
    public function test_store_2fa_code_generates_and_stores_code()
    {
        // Create a User and a corresponding UserInfo
        $user = User::factory()->create(); // Password and auth-related fields
        $userInfo = UserInfo::factory()->create(['id' => $user->id]); // Additional details

        // Authenticate the user
        $this->actingAs($user);

        // Trigger the endpoint
        $response = $this->postJson(route('2fa.store'));

        // Assert response structure and status
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'code'])
            ->assertJson(['status' => 'success', 'message' => '2FA code generated successfully']);

        // Assert code is stored in cache
        $this->assertNotNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_success()
    {
        // Create a User and a corresponding UserInfo
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Store a 2FA code in the cache
        $code = random_int(100000, 999999);
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        // Send the correct 2FA code for verification
        $response = $this->post(route('2fa.verify'), ['2fa_code' => $code]);

        // Assert redirection and success message
        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas('success', '2FA verification successful!');

        // Assert code is removed from cache
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_fails_with_incorrect_code()
    {
        // Create a User and a corresponding UserInfo
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Store a correct 2FA code in the cache
        $correctCode = random_int(100000, 999999);
        Cache::put('2fa_code_' . $user->id, $correctCode, now()->addMinutes(10));

        // Send an incorrect code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => '123456']);

        // Assert error message
        $response->assertRedirect()
            ->assertSessionHasErrors(['2fa_code' => 'The code you entered is incorrect. Please try again.']);

        // Ensure the correct code is still in cache
        $this->assertEquals($correctCode, Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_2fa_code_fails_when_code_not_in_cache()
    {
        // Create a User and a corresponding UserInfo
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Ensure no 2FA code exists in the cache
        Cache::forget('2fa_code_' . $user->id);

        // Send a 2FA code for verification
        $response = $this->post(route('2fa.verify'), ['2fa_code' => '123456']);

        // Assert error message
        $response->assertRedirect()
            ->assertSessionHasErrors(['2fa_code' => 'The code you entered is incorrect. Please try again.']);

        // Ensure the cache is still empty
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }
}