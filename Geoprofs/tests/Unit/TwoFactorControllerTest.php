<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class TwoFactorControllerTest extends TestCase
{
    /**
     * Test that the storeCode method generates and stores a 2FA code.
     */
    public function test_store_code_generates_and_stores_2fa_code()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Call the storeCode route
        $response = $this->postJson(route('2fa.store'));

        // Assert the response is successful
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => '2FA code generated successfully',
                 ]);

        // Check that the 2FA code is stored in the cache
        $cachedCode = Cache::get('2fa_code_' . $user->id);
        $this->assertNotNull($cachedCode);
        $this->assertIsNumeric($cachedCode);
        $this->assertGreaterThanOrEqual(100000, $cachedCode);
        $this->assertLessThanOrEqual(999999, $cachedCode);
    }

    /**
     * Test that the verifyCode method validates a correct 2FA code.
     */
    public function test_verify_code_succeeds_with_correct_code()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Store a valid 2FA code in the cache
        $code = 123456;
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        // Call the verifyCode route with the correct code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => $code]);

        // Assert redirection to the dashboard with a success message
        $response->assertRedirect(route('dashboard'))
                 ->assertSessionHas('success', '2FA verification successful!');

        // Assert the cached code is cleared
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }

    /**
     * Test that the verifyCode method fails with an incorrect 2FA code.
     */
    public function test_verify_code_fails_with_incorrect_code()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Store a valid 2FA code in the cache
        $code = 123456;
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        // Call the verifyCode route with an incorrect code
        $response = $this->post(route('2fa.verify'), ['2fa_code' => 654321]);

        // Assert that the user is redirected back with an error
        $response->assertRedirect()
                 ->assertSessionHasErrors(['2fa_code' => 'The code you entered is incorrect. Please try again.']);

        // Assert the cached code is still present
        $this->assertEquals($code, Cache::get('2fa_code_' . $user->id));
    }

    /**
     * Test that the verifyCode method validates the input correctly.
     */
    public function test_verify_code_fails_with_invalid_input()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Call the verifyCode route with invalid input
        $response = $this->post(route('2fa.verify'), ['2fa_code' => 'not_a_number']);

        // Assert validation errors are returned
        $response->assertRedirect()
                 ->assertSessionHasErrors(['2fa_code']);
    }
}