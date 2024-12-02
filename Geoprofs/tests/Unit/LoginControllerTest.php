<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the login form is displayed.
     */
    public function test_show_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test successful login.
     */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa.show'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with invalid credentials.
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test login fails when account is locked.
     */
    public function test_login_fails_when_account_is_locked()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'account_locked' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test login fails when blocked until a specific time.
     */
    public function test_login_fails_when_blocked_until()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'blocked_until' => now()->addMinutes(5),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test storing the 2FA code.
     */
    public function test_store_2fa_code()
    {
        $response = $this->postJson('/2fa/code', ['code' => '123456']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'message' => 'Code stored successfully']);
        $this->assertTrue(Cache::has('2fa_code'));
    }

    /**
     * Test verifying the 2FA code.
     */
    public function test_verify_2fa_code()
    {
        Cache::put('2fa_code', '123456', now()->addMinutes(10));

        $response = $this->post('/2fa/verify', ['2fa_code' => '123456']);

        $response->assertRedirect(route('dashboard'));
        $this->assertFalse(Cache::has('2fa_code')); // Assert the code is cleared
    }

    /**
     * Test verifying 2FA with incorrect code.
     */
    public function test_verify_2fa_code_fails_with_incorrect_code()
    {
        Cache::put('2fa_code', '123456', now()->addMinutes(10));

        $response = $this->post('/2fa/verify', ['2fa_code' => '654321']);

        $response->assertSessionHasErrors(['2fa_code']);
        $this->assertTrue(Cache::has('2fa_code')); // Assert the code is still in the cache
    }

    /**
     * Test logging out.
     */
    public function test_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}