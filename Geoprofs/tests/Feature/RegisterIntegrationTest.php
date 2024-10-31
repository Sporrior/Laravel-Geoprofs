<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // Controleer of de validatie faalt
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('users', ['email' => 'janedoe@example.com']);
    }
}
