<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_form()
    {
        $response = $this->get(route('login.form'));

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_successful_login()
    {
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'email' => 'test@example.com',
        ]);

        $user->update(['password' => Hash::make('password123')]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa.show'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_email()
    {
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_with_invalid_password()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create([
            'id' => $user->id,
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_account_temporary_block_after_failed_attempts()
    {
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'email' => 'test@example.com',
            'failed_login_attempts' => 4,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Too many failed login attempts. Try again in 15 minutes.']);

        $userInfo->refresh();
        $this->assertNotNull($userInfo->blocked_until);
        $this->assertGuest();
    }

    public function test_account_permanently_locked()
    {
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'email' => 'test@example.com',
            'account_locked' => true,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Your account is permanently locked due to multiple failed login attempts.']);
        $this->assertGuest();
    }

    public function test_show_2fa_form()
    {
        $response = $this->get(route('2fa.show'));

        $response->assertStatus(200);
        $response->assertViewIs('2fa');
    }

    public function test_store_2fa_code_from_app()
    {
        $response = $this->post(route('2fa.store'), ['code' => '123456']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'message' => 'Code stored successfully']);

        $this->assertEquals('123456', Cache::get('2fa_code'));
    }

    public function test_verify_correct_2fa_code()
    {
        Cache::put('2fa_code', '123456', now()->addMinutes(10));

        $response = $this->post(route('2fa.verify'), ['2fa_code' => '123456']);

        $response->assertRedirect(route('dashboard'));
        $this->assertNull(Cache::get('2fa_code'));
    }

    public function test_verify_incorrect_2fa_code()
    {
        Cache::put('2fa_code', '123456', now()->addMinutes(10));

        $response = $this->post(route('2fa.verify'), ['2fa_code' => '654321']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['2fa_code' => 'The 2FA code is incorrect.']);
        $this->assertNotNull(Cache::get('2fa_code'));
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}