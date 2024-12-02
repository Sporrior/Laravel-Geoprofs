<?php

namespace Tests\Feature;

use App\Http\Controllers\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_redirects_to_dashboard()
    {
        // Maak de registratiegegevens aan
        $registrationData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        //  POST request naar de register route
        $response = $this->post(route('register'), $registrationData);

        // controleer of de gebruiker in de database is aangemaakt
        $this->assertDatabaseHas('users', [
            'voorNaam' => 'Jane',
            'achterNaam' => 'Doe',
            'email' => 'jane.doe@example.com',
        ]);

        // Controleer de redirect naar het dashboard
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_registration_fails_with_missing_name()
    {
        // oorbereid incomplete registratiegegevens
        $registrationData = [
            'email' => 'janedoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // probeer te registreren zonder naam
        $response = $this->post(route('register'), $registrationData);

        // Controleer of de validatie faalt en foutmeldingen bevat voor de naam
        $response->assertSessionHasErrors(['name']);
        // Controleer of de gebruiker niet is aangemaakt
        $this->assertDatabaseMissing('users', ['email' => 'janedoe@example.com']);
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

        // Maak een request met hetzelfde e-mailadres
        $request = Request::create('/register', 'POST', $data);

        // Maak een nieuwe instantie van de RegisterController
        $controller = new RegisterController();

        // Verwacht een ValidationException
        $this->expectException(ValidationException::class);
        // Probeer de registratie uit te voeren
        $controller->register($request);

        // Controleer dat er geen extra gebruiker is aangemaakt
        $this->assertEquals(1, User::where('email', 'jane@example.com')->count());
    }
}
