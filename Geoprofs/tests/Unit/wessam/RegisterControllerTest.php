<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_new_user_and_logs_them_in()
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '0612345678',
        ]);

        // kijk of er een record in de 'users' tabel is aangemaakt ... 
        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('user_info', [
            'voornaam' => 'John',
            'tussennaam' => null,
            'achternaam' => 'Doe',
            'email' => 'johndoe@example.com',
            'telefoon' => '0612345678',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function it_validates_registration_data()
    {
        $request = new Request([
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
            'telefoon' => '',
        ]);

        $controller = new \App\Http\Controllers\RegisterController();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->register($request);
    }

    /** @test */
    public function it_handles_users_with_middle_name()
    {
        $request = new Request([
            'name' => 'John Michael Doe',
            'email' => 'johnmichael@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefoon' => '0612345678',
        ]);

        $controller = new \App\Http\Controllers\RegisterController();
        $response = $controller->register($request);

        $this->assertDatabaseHas('user_info', [
            'voornaam' => 'John',
            'tussennaam' => 'Michael',
            'achternaam' => 'Doe',
            'email' => 'johnmichael@example.com',
            'telefoon' => '0612345678',
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/dashboard', $response->headers->get('Location'));
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        UserInfo::create([
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'johndoe@example.com',
            'telefoon' => '0612345678',
        ]);

        $response = $this->post(route('register.submit'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
    
    /** @test */
    public function it_rejects_invalid_passwords()
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'telefoon' => '0612345678',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

}