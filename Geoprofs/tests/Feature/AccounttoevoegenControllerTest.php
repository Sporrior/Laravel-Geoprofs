<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AccounttoevoegenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_new_user_and_user_info()
{
    // Ensure a clean state
    $this->refreshDatabase();

    // Use a factory to create a role or check existence
    $role = Role::firstOrCreate(['role_name' => 'manager']);

    // Create a user to authenticate
    $adminUser = User::factory()->create();
    $this->actingAs($adminUser);

    // Prepare input data
    $data = [
        'voornaam' => 'John',
        'tussennaam' => 'van',
        'achternaam' => 'Doe',
        'telefoon' => '1234567890',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role_id' => $role->id,
    ];

    // Perform the POST request
    $response = $this->post(route('account-toevoegen.store'), $data);

    // Assert redirection
    $response->assertRedirect(route('account-toevoegen.index'));
    $response->assertSessionHas('success', 'Gebruiker succesvol aangemaakt.');

    // Assert database entries
    $this->assertDatabaseHas('users', [
        'id' => 1,
    ]);

    $this->assertDatabaseHas('user_info', [
        'email' => 'john.doe@example.com',
        'voornaam' => 'John',
        'achternaam' => 'Doe',
        'role_id' => $role->id,
    ]);
}



//     public function test_store_validates_input()
// {
//     // Seed roles
//     $role = Role::factory()->create(['role_name' => 'manager']);

//     // Authenticate a user
//     $adminUser = User::factory()->create();
//     $this->actingAs($adminUser);

//     // Prepare invalid input data
//     $data = [
//         'voornaam' => '',
//         'achternaam' => '',
//         'telefoon' => '1234567890',
//         'email' => 'not-an-email',
//         'password' => 'short',
//         'password_confirmation' => 'short',
//         'role_id' => 999,
//     ];

//     // Perform the POST request
//     $response = $this->post(route('account-toevoegen.store'), $data);

//     // Assert validation errors
//     $response->assertSessionHasErrors(['voornaam', 'achternaam', 'email', 'password', 'role_id']);
// }

}
