<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class RegisterIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_redirects_to_dashboard()
    {
        // Arrange: Create the registration data
        $registrationData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send a POST request to the register route
        $response = $this->post(route('register'), $registrationData);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'voorNaam' => 'Jane',
            'achterNaam' => 'Doe',
            'email' => 'jane.doe@example.com',
        ]);

        // Check for the redirect to the dashboard
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_registration_fails_with_missing_name()
    {
        //  Prepare incomplete registration data (missing name)
        $registrationData = [
            'email' => 'janedoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Attempt to register without a name
        $response = $this->post(route('register'), $registrationData);

        // Check that validation fails
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('users', ['email' => 'janedoe@example.com']);
    }
}
