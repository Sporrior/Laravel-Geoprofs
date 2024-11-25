<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['role_name' => 'werknemer']);
        Role::firstOrCreate(['role_name' => 'manager']);
        Role::firstOrCreate(['role_name' => 'officemanagement']);

        $this->createTeams();

        $this->createUsers();

        $this->call(VerlofaanvragenSeeder::class);
    }
    private function createTeams()
    {
        Team::firstOrCreate(['group_name' => 'GeoICT']);
        Team::firstOrCreate(['group_name' => 'GeoDECY']);
        Team::firstOrCreate(['group_name' => 'HRM']);
        Team::firstOrCreate(['group_name' => 'Finances']);
        Team::firstOrCreate(['group_name' => 'ICT']);
        Team::firstOrCreate(['group_name' => 'OM']);
    }

    private function createUsers()
    {
        $geoICT = Team::where('group_name', 'GeoICT')->first()->id;
        $geoDECY = Team::where('group_name', 'GeoDECY')->first()->id;
        $hrm = Team::where('group_name', 'HRM')->first()->id;
        $finances = Team::where('group_name', 'Finances')->first()->id;
        $ICT = Team::where('group_name', 'ICT')->first()->id;
        $OM = Team::where('group_name', 'OM')->first()->id;

        User::factory()->create([
            'voornaam' => 'Ahmad',
            'achternaam' => 'Mahouk',
            'telefoon' => '06123123123',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('Ahmad'),
            'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
            'team_id' => $hrm, // Assign to GeoICT team
        ]);

        User::factory()->create([
            'voornaam' => 'Damien',
            'achternaam' => 'Engelen',
            'telefoon' => '06123456789',
            'email' => 'damien@gmail.com',
            'password' => Hash::make('Damien12345'),
            'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
            'team_id' => $hrm, 
        ]);

        User::factory()->create([
            'voornaam' => 'Wessam',
            'achternaam' => 'gold',
            'telefoon' => '06234567890',
            'email' => 'wessam@gmail.com',
            'password' => Hash::make('wessam12345'),
            'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
            'team_id' => $hrm, 
        ]);

        User::factory()->create([
            'voornaam' => 'Testen',
            'achternaam' => 'Smith',
            'telefoon' => '06234567890',
            'email' => 'testen@gmail.com',
            'password' => Hash::make('testen'),
            'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
            'team_id' => $OM, 
        ]);

        User::factory()->create([
            'voornaam' => 'Karel',
            'achternaam' => 'gold',
            'telefoon' => '06234567890',
            'email' => 'karel@gmail.com',
            'password' => Hash::make('Karel'),
            'role_id' => Role::where('role_name', 'werknemer')->first()->id,
            'team_id' => $geoICT,
        ]);

        User::factory()->create([
            'voornaam' => 'Bob',
            'achternaam' => 'Bouwer',
            'telefoon' => '06234567890',
            'email' => 'bob@gmail.com',
            'password' => Hash::make('Bob'),
            'role_id' => Role::where('role_name', 'werknemer')->first()->id,
            'team_id' => $geoICT,
        ]);

        User::factory()->create([
            'voornaam' => 'Samet',
            'achternaam' => 'Lahmacun',
            'telefoon' => '06234567890',
            'email' => 'samet@gmail.com',
            'password' => Hash::make('Samet'),
            'role_id' => Role::where('role_name', 'werknemer')->first()->id,
            'team_id' => $geoICT,
        ]);

        User::factory()->create([
            'voornaam' => 'Kees',
            'achternaam' => 'van der Veen',
            'telefoon' => '06234567890',
            'email' => 'kees@gmail.com',
            'password' => Hash::make('Kees'),
            'role_id' => Role::where('role_name', 'werknemer')->first()->id,
            'team_id' => $geoICT,
        ]);
    }
}
