<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserInfo;

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
        $hrm = Team::where('group_name', 'HRM')->first()->id;
        $OM = Team::where('group_name', 'OM')->first()->id;
    
        $users = [
            [
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('Ahmad'),
                'info' => [
                    'voornaam' => 'Ahmad',
                    'achternaam' => 'Mahouk',
                    'telefoon' => '06123123123',
                    'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
                    'team_id' => $hrm,
                ],
            ],
            [
                'email' => 'damien@gmail.com',
                'password' => Hash::make('Damien12345'),
                'info' => [
                    'voornaam' => 'Damien',
                    'achternaam' => 'Engelen',
                    'telefoon' => '06123456789',
                    'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
                    'team_id' => $hrm,
                ],
            ],
            [
                'email' => 'wessam@gmail.com',
                'password' => Hash::make('wessam12345'),
                'info' => [
                    'voornaam' => 'Wessam',
                    'achternaam' => 'Gold',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
                    'team_id' => $hrm,
                ],
            ],
            [
                'email' => 'testen@gmail.com',
                'password' => Hash::make('testen'),
                'info' => [
                    'voornaam' => 'Testen',
                    'achternaam' => 'Smith',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
                    'team_id' => $OM,
                ],
            ],
            [
                'email' => 'karel@gmail.com',
                'password' => Hash::make('Karel'),
                'info' => [
                    'voornaam' => 'Karel',
                    'achternaam' => 'Gold',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
            [
                'email' => 'bob@gmail.com',
                'password' => Hash::make('Bob'),
                'info' => [
                    'voornaam' => 'Bob',
                    'achternaam' => 'Bouwer',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
            [
                'email' => 'samet@gmail.com',
                'password' => Hash::make('Samet'),
                'info' => [
                    'voornaam' => 'Samet',
                    'achternaam' => 'Lahmacun',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
            [
                'email' => 'kees@gmail.com',
                'password' => Hash::make('Kees'),
                'info' => [
                    'voornaam' => 'Kees',
                    'achternaam' => 'van der Veen',
                    'telefoon' => '06234567890',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
        ];
    
        foreach ($users as $userData) {
            $user = User::create([
                'password' => $userData['password'],
            ]);
    
            UserInfo::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'id' => $user->id,
                    'voornaam' => $userData['info']['voornaam'],
                    'achternaam' => $userData['info']['achternaam'],
                    'telefoon' => $userData['info']['telefoon'],
                    'role_id' => $userData['info']['role_id'],
                    'team_id' => $userData['info']['team_id'],
                ]
            );
        }
    }
}