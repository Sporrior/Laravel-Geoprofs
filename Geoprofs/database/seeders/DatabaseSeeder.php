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
        $this->createRoles();
        $this->createTeams();
        $this->createUsers();

        $this->call(VerlofaanvragenSeeder::class);
    }

    private function createRoles()
    {
        $roles = ['werknemer', 'manager', 'officemanagement'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role]);
        }
    }

    private function createTeams()
    {
        $teams = ['GeoICT', 'GeoDECY', 'HRM', 'Finances', 'ICT', 'OM'];

        foreach ($teams as $team) {
            Team::firstOrCreate(['group_name' => $team]);
        }
    }

    private function createUsers()
    {
        $geoICT = Team::where('group_name', 'GeoICT')->first()->id;
        $hrm = Team::where('group_name', 'HRM')->first()->id;
        $OM = Team::where('group_name', 'OM')->first()->id;

        $users = [
            [
                'email' => 'wessam@gmail.com',
                'password' => Hash::make('Wess123456'),
                'info' => [
                    'voornaam' => 'Wess',
                    'achternaam' => 'Boy',
                    'telefoon' => '06123123123',
                    'role_id' => Role::where('role_name', 'officemanagement')->first()->id,
                    'team_id' => $hrm,
                ],
            ],
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
                'email' => 'User01@gmail.com',
                'password' => Hash::make('User01'),
                'info' => [
                    'voornaam' => 'User01',
                    'achternaam' => 'back',
                    'telefoon' => '06123456789',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $hrm,
                ],
            ],
            [
                'email' => 'User02@gmail.com',
                'password' => Hash::make('User02'),
                'info' => [
                    'voornaam' => 'User02',
                    'achternaam' => 'back',
                    'telefoon' => '06123456789',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
            [
                'email' => 'User03@gmail.com',
                'password' => Hash::make('User03'),
                'info' => [
                    'voornaam' => 'User03',
                    'achternaam' => 'back',
                    'telefoon' => '06123456789',
                    'role_id' => Role::where('role_name', 'werknemer')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
            [
                'email' => 'User04@gmail.com',
                'password' => Hash::make('User04'),
                'info' => [
                    'voornaam' => 'User04',
                    'achternaam' => 'back',
                    'telefoon' => '06123456789',
                    'role_id' => Role::where('role_name', 'manager')->first()->id,
                    'team_id' => $geoICT,
                ],
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['id' => UserInfo::where('email', $userData['email'])->value('id')],
                ['password' => $userData['password']]
            );

            UserInfo::updateOrCreate(
                ['id' => $user->id],
                array_merge($userData['info'], ['email' => $userData['email']])
            );
        }
    }
}
