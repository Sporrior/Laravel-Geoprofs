<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_form()
    {
        $response = $this->get(route('login.show'));

        $response->assertStatus(200)
            ->assertViewIs('login');
    }

    public function test_login_successfully()
    {
        $userInfo = UserInfo::factory()->create([
            'email' => 'test@example.com',
        ]);

        $user = User::factory()->create([
            'id' => $userInfo->id,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa.show'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('user_infos', [
            'id' => $userInfo->id,
            'failed_login_attempts' => 0,
            'blocked_until' => null,
        ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        UserInfo::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_fails_with_locked_account()
    {
        $userInfo = UserInfo::factory()->create([
            'email' => 'test@example.com',
            'account_locked' => true,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email' => 'Your account is permanently locked due to multiple failed login attempts.']);
        $this->assertGuest();
    }

    public function test_login_fails_with_temporarily_blocked_account()
    {
        $userInfo = UserInfo::factory()->create([
            'email' => 'test@example.com',
            'blocked_until' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_fails_with_exceeded_failed_attempts()
    {
        $userInfo = UserInfo::factory()->create([
            'email' => 'test@example.com',
            'failed_login_attempts' => 4,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email' => 'Too many failed login attempts. Try again in 15 minutes.']);
        $this->assertGuest();

        $this->assertDatabaseHas('user_infos', [
            'id' => $userInfo->id,
            'failed_login_attempts' => 5,
        ]);
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