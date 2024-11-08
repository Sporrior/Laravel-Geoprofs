<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Logboek;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ProfielControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChangePassword()
    {
        // Ensure a 'werknemer' role exists, using firstOrCreate to prevent duplicates
        $role = Role::firstOrCreate(['roleName' => 'werknemer']);

        // Create a team for the user
        $team = Team::factory()->create(['name' => 'Development Team']);

        // Create a user with all required fields and relationships
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
            'voornaam' => 'Jan',
            'tussennaam' => 'van',
            'achternaam' => 'Jansen',
            'profielFoto' => 'profile_pictures/default_profile_photo.png',
            'telefoon' => '0612345678',
            'email' => 'test@gmail.com',
            'verlof_dagen' => 25,
            'role_id' => $role->id, // Associate with existing 'werknemer' role
            'team_id' => $team->id,
        ]);

        // New password data for the request
        $newPasswordData = [
            'huidigWachtwoord' => 'oldpassword',
            'nieuwWachtwoord' => 'newpassword123',
            'nieuwWachtwoord_confirmation' => 'newpassword123',
        ];

        // Act as the user and attempt to change the password using PUT
        $response = $this->actingAs($user)->put(route('profiel.changePassword'), $newPasswordData);

        // Verify that we get a redirect and success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Wachtwoord succesvol gewijzigd');

        // Refresh the user instance and check if the password was updated
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Confirm that the action was logged in the logboek table
        $this->assertDatabaseHas('logboeks', [
            'user_id' => $user->id,
            'actie' => 'Password changed door gebruiker: Jan Jansen',
            'actie_beschrijving' => 'Wachtwoord is gewijzigd',
        ]);
    }
}
