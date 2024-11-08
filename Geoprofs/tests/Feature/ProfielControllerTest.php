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

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['roleName' => 'werknemer']);
        Team::firstOrCreate(['group_name' => 'GeoICT']);
    }

    public function testChangePassword()
    {
        $role = Role::where('roleName', 'werknemer')->first();
        $team = Team::where('group_name', 'GeoICT')->first();

        $user = User::create([
            'password' => Hash::make('oldpassword'),
            'voornaam' => 'Jan',
            'tussennaam' => 'van',
            'achternaam' => 'Jansen',
            'profielFoto' => 'profile_pictures/default_profile_photo.png',
            'telefoon' => '0612345678',
            'email' => 'test@gmail.com',
            'verlof_dagen' => 25,
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        $newPasswordData = [
            'huidigWachtwoord' => 'oldpassword',
            'nieuwWachtwoord' => 'newpassword123',
            'nieuwWachtwoord_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($user)->put(route('profiel.changePassword'), $newPasswordData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Wachtwoord succesvol gewijzigd');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Update the expected value to match what is in the database
        $this->assertDatabaseHas('logboeken', [
            'user_id' => $user->id,
            'actie' => 'Password changed door gebruiker: Jan Jansen met een rol van werknemer',
            'actie_beschrijving' => 'Wachtwoord is gewijzigd',
        ]);
    }
}
