<?php

namespace Tests\Unit\damien;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_form()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_successful_login()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create([
            'id' => $user->id,
            'email' => 'test@example.com',
        ]);

        $user->update(['password' => Hash::make('password123')]);

        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa.show'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_email()
    {
        $response = $this->post(route('login.submit'), [
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

        $response = $this->post(route('login.submit'), [
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

        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Too many failed login attempts. Try again in 15 minutes.']);
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

        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => 'Your account is permanently locked due to multiple failed login attempts.']);
        $this->assertGuest();
    }

    public function test_store_2fa_code_from_app()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('2fa.store'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'code',
        ]);

        $storedCode = Cache::get('2fa_code_' . $user->id);
        $this->assertNotNull($storedCode);
        $this->assertEquals($response->json('code'), $storedCode);
    }

    public function test_verify_correct_2fa_code()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $code = random_int(100000, 999999);
        Cache::put('2fa_code_' . $user->id, $code, now()->addMinutes(10));

        $response = $this->post(route('2fa.verify'), ['2fa_code' => $code]);

        $response->assertRedirect(route('dashboard'));
        $this->assertNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_verify_incorrect_2fa_code()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Cache::put('2fa_code_' . $user->id, '123456', now()->addMinutes(10));

        $response = $this->post(route('2fa.verify'), ['2fa_code' => '654321']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['2fa_code' => 'The code you entered is incorrect. Please try again.']);
        $this->assertNotNull(Cache::get('2fa_code_' . $user->id));
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}