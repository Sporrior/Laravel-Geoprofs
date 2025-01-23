<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_new_user_and_logs_them_in()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '1234567890',
        ];

        $response = $this->post(route('register'), $data);

        // Check database for correct data
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'voornaam' => 'John',
            'tussennaam' => null,
            'achternaam' => 'Doe',
            'telefoon' => '1234567890',
        ]);

        // Check if the user is authenticated
        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_handles_users_with_two_names()
    {
        $data = [
            'name' => 'John Michael Doe',
            'email' => 'johnmichael@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '0987654321',
        ];

        $response = $this->post(route('register'), $data);

        // Check database for correct data
        $this->assertDatabaseHas('users', [
            'email' => 'johnmichael@example.com',
            'voornaam' => 'John',
            'tussennaam' => 'Michael',
            'achternaam' => 'Doe',
            'telefoon' => '0987654321',
        ]);

        // Check if the user is authenticated
        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_handles_users_with_no_middle_name()
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '5555555555',
        ];

        $response = $this->post(route('register'), $data);

        // Check database for correct data
        $this->assertDatabaseHas('users', [
            'email' => 'janedoe@example.com',
            'voornaam' => 'Jane',
            'tussennaam' => null,
            'achternaam' => 'Doe',
            'telefoon' => '5555555555',
        ]);

        // Check if the user is authenticated
        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_validates_registration_data()
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['name', 'email', 'telefoon']);
    }

    /** @test */
    public function it_validates_password_confirmation()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
            'telefoon' => '1112223333',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        // Create a user with the same email
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '2223334444',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['email']);
    }
}