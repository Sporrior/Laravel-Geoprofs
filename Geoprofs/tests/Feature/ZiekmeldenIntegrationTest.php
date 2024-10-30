<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        // Arrange: Definieer registratiegegevens
        $data = [
            'name'                  => 'Jane Doe',
            'email'                 => 'jane@example.com',
            'password'              => 'securepassword',
            'password_confirmation' => 'securepassword',
            'telefoon'              => '0612345678', // Voeg 'telefoon' toe
        ];

        // Act: Verstuur een POST-aanvraag naar de register route
        $response = $this->post(route('register'), $data);

        // Extract 'voornaam' en 'achternaam' zoals de controller dat doet
        $nameParts = explode(' ', $data['name']);
        $voornaam = $nameParts[0];
        $tussennaam = count($nameParts) === 3 ? $nameParts[1] : null;
        $achternaam = count($nameParts) > 1 ? end($nameParts) : '';

        // Assert: Controleer of de gebruiker in de database is aangemaakt
        $this->assertDatabaseHas('users', [
            'email'     => 'jane@example.com',
            'voornaam'  => $voornaam,
            'achternaam'=> $achternaam,
            'telefoon'  => '0612345678',
        ]);

        // Assert: Controleer of de gebruiker is geauthenticeerd en doorverwezen
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_register_fails_when_password_is_too_short()
    {
        // Arrange: Definieer registratiegegevens met een te kort wachtwoord
        $data = [
            'name'                  => 'Jane Doe',
            'email'                 => 'jane@example.com',
            'password'              => 'short',
            'password_confirmation' => 'short',
            'telefoon'              => '0612345678', // Voeg 'telefoon' toe
        ];

        // Act: Verstuur een POST-aanvraag naar de register route
        $response = $this->post(route('register'), $data);

        // Assert: Controleer of de validatie faalt voor het wachtwoord
        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }
}
