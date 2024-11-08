<?php

namespace Tests\Unit;

use App\Http\Controllers\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_with_correct_data()
    {
        // Maak een request met alle vereiste velden
        $data = [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $request = Request::create('/register', 'POST', $data);

        // Roep de register methode aan
        $controller = new RegisterController();
        $response = $controller->register($request);

        // Controleer of de gebruiker is angemaakt in de database
        $this->assertDatabaseHas('users', [
            'email'     => 'john@example.com',
            'voornaam'  => 'John',
            'achternaam'=> 'Doe',
            'telefoon'  => '',
            'profielFoto' => null, // Is nullable, dus maakt niet uit
        ]);

        // Controleer of de gebruiker is geauthenticeerd
        $this->assertAuthenticated();

        // Controleer of de gebruiker wordt doorgestuurd naar '/dashboard' en niet naar '/login'
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(url('/dashboard'), $response->headers->get('Location'));
    }

    public function test_register_fails_with_missing_name()
    {
        // Maak een request zonder 'name'
        $data = [
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $request = Request::create('/register', 'POST', $data);

        $controller = new RegisterController();

        //  Verwacht een foutmelding
        $this->expectException(ValidationException::class);
        $controller->register($request);

        // Controleer dat de gebruiker niet is aangemaakt
        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_register_fails_with_duplicate_email()
    {
        // Maak een bestaande gebruiker aan met alle vereiste velden
        User::create([
            'voornaam'    => 'Jane',
            'tussennaam'  => null,
            'achternaam'  => 'Doe',
            'profielFoto' => '', // Vul een niet-NULL waarde in
            'telefoon'    => '', // Aangezien dit veld verplicht is
            'email'       => 'jane@example.com',
            'password'    => Hash::make('existingpassword'),
        ]);

        // Maak een request met hetzelfde e-mailadres
        $data = [
            'name'                  => 'Jane Smith',
            'email'                 => 'jane@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $request = Request::create('/register', 'POST', $data);

        $controller = new RegisterController();

        // Verwacht een ValidationException
        $this->expectException(ValidationException::class);
        $controller->register($request);

        // Controleer dat er geen extra gebruiker is aangemaakt
        $this->assertEquals(1, User::where('email', 'jane@example.com')->count());
    }
}
