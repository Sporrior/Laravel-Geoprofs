<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanBeCreatedSuccessfully()
    {
        // Arrange: Prepare the user data for the new user
        $userData = [
            'voornaam' => 'John',
            'tussennaam' => 'Doe',
            'achternaam' => 'Smith',
            'telefoon' => '06123456789',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Manually create a user to act as the logged-in user
        $user = new User();
        $user->voornaam = 'Jane';
        $user->tussennaam = 'Doe';
        $user->achternaam = 'Johnson';
        $user->telefoon = '06123456780';
        $user->email = 'jane@example.com';
        $user->password = bcrypt('password123'); // Hash the password
        $user->save(); // Save the user to the database

        // Act: Simulate the user logged in
        $this->actingAs($user);

        // Simulate a POST request to the store method
        $response = $this->post(route('addusers.store'), $userData);

        // Assert: Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'voornaam' => 'John',
            'achternaam' => 'Smith',
            'email' => 'john@example.com',
        ]);

        // Assert: Check for a redirect back with a success message
        $response->assertRedirect(route('addusers.index'));
        $response->assertSessionHas('success', 'User successfully created.');
    }

    public function testUserCreationFailsWithValidationErrors()
    {
        // Arrange: Prepare data with validation errors (missing required fields)
        $userData = [
            'voornaam' => '',  // Required field left empty
            'tussennaam' => 'Doe',
            'achternaam' => '', // Required field left empty
            'telefoon' => '06123456789',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Manually create a user to act as the logged-in user
        $user = new User();
        $user->voornaam = 'Jane';
        $user->tussennaam = 'Doe';
        $user->achternaam = 'Johnson';
        $user->telefoon = '06123456780';
        $user->email = 'jane@example.com';
        $user->password = bcrypt('password123'); // Hash the password
        $user->save(); // Save the user to the database

        // Act: Simulate the user logged in
        $this->actingAs($user);

        // Simulate a POST request to the store method
        $response = $this->post(route('addusers.store'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['voornaam', 'achternaam']);
    }
}
