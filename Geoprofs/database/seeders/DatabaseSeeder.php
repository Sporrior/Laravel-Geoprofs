<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Verlofaanvragen; // Ensure this matches the model name
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if not already created
        Role::firstOrCreate(['roleName' => 'werknemer']);
        Role::firstOrCreate(['roleName' => 'manager']);
        Role::firstOrCreate(['roleName' => 'officemanagement']);

        // Create teams if not already created
        $this->createTeams();

        // Create users with different roles and assign teams
        $this->createUsers();

        // Seed the verlofaanvragen table
        $this->call(VerlofaanvragenSeeder::class);
    }

    private function createTeams()
    {
        // Create teams
        Team::firstOrCreate(['group_name' => 'GeoICT']);
        Team::firstOrCreate(['group_name' => 'GeoDECY']);
        Team::firstOrCreate(['group_name' => 'HRM']);
        Team::firstOrCreate(['group_name' => 'Finances']);
    }

    private function createUsers()
    {
        // Retrieve the specific team IDs
        $geoICT = Team::where('group_name', 'GeoICT')->first()->id;
        $geoDECY = Team::where('group_name', 'GeoDECY')->first()->id;
        $hrm = Team::where('group_name', 'HRM')->first()->id;
        $finances = Team::where('group_name', 'Finances')->first()->id;

        // Create a user with the werknemer role in GeoICT
        User::factory()->create([
            'voornaam' => 'Ahmad',
            'achternaam' => 'Mahouk',
            'telefoon' => '06123123123',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('Ahmad'),
            'role_id' => Role::where('roleName', 'werknemer')->first()->id,
            'team_id' => $geoICT, // Assign to GeoICT team
        ]);

        // Create a user with the manager role in GeoDECY
        User::factory()->create([
            'voornaam' => 'Damien',
            'achternaam' => 'Doe',
            'telefoon' => '06123456789',
            'email' => 'damien@gmail.com',
            'password' => Hash::make('Damien'),
            'role_id' => Role::where('roleName', 'manager')->first()->id,
            'team_id' => $geoDECY, // Assign to GeoDECY team
        ]);

        // Create a user with the officemanagement role in HRM
        User::factory()->create([
            'voornaam' => 'Wassem',
            'achternaam' => 'Smith',
            'telefoon' => '06234567890',
            'email' => 'wassem@gmail.com',
            'password' => Hash::make('Wassem'),
            'role_id' => Role::where('roleName', 'officemanagement')->first()->id,
            'team_id' => $hrm, // Assign to HRM team
        ]);

        // Additional test user in Finances
        User::factory()->create([
            'voornaam' => 'Testen',
            'achternaam' => 'Smith',
            'telefoon' => '06234567890',
            'email' => 'testen@gmail.com',
            'password' => Hash::make('testen'),
            'role_id' => Role::where('roleName', 'officemanagement')->first()->id,
            'team_id' => $finances, // Assign to Finances team
        ]);
    }
}
