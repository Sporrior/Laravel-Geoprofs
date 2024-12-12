<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_register_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    public function test_register_success()
    {
        $requestData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $requestData);

        $response->assertRedirect('/dashboard');

        // Check UserInfo
        $this->assertDatabaseHas('user_info', [
            'email' => 'john@example.com',
        ]);

        $userInfo = UserInfo::where('email', 'john@example.com')->first();
        $this->assertNotNull($userInfo);
        $this->assertEquals('John', $userInfo->voornaam);
        $this->assertEquals('Doe', $userInfo->achternaam);

        // Check User
        $user = User::find($userInfo->id);
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_register_validation_errors()
    {
        $response = $this->post('/register', [
            'name' => '', // Missing name
            'email' => 'invalid-email', // Invalid email
            'password' => 'short', // Password too short
            'password_confirmation' => 'mismatch', // Password mismatch
        ]);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);
    }

    public function test_register_with_duplicate_email()
    {
        UserInfo::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('user_info', 1);
    }
}
