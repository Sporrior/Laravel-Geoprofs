<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_registration_form()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    /** @test */
    public function it_registers_a_new_user_and_logs_them_in()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $this->assertDatabaseHas('user_info', [
            'email' => 'johndoe@example.com',
            'voornaam' => 'John',
            'tussennaam' => null,
            'achternaam' => 'Doe',
        ]);

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
        ];

        $response = $this->post(route('register'), $data);

        $this->assertDatabaseHas('user_info', [
            'email' => 'johnmichael@example.com',
            'voornaam' => 'John',
            'tussennaam' => 'Michael',
            'achternaam' => 'Doe',
        ]);

        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_handles_users_with_no_middle_name()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $this->assertDatabaseHas('user_info', [
            'email' => 'johndoe2@example.com',
            'voornaam' => 'John',
            'tussennaam' => null,
            'achternaam' => 'Doe',
        ]);

        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_validates_registration_data()
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    /** @test */
    public function it_requires_name_to_split_into_first_middle_last_names()
    {
        $data = [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $this->assertDatabaseHas('user_info', [
            'email' => 'john@example.com',
            'voornaam' => 'John',
            'tussennaam' => null,
            'achternaam' => '',
        ]);

        $this->assertTrue(Auth::check());

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_validates_password_confirmation()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        $existingUser = UserInfo::factory()->create([
            'email' => 'john@example.com'
        ]);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['email']);
    }
}
