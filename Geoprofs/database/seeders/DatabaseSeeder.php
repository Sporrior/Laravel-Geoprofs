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
            // Add more users as needed...
        ];

        foreach ($users as $userData) {
            // Create or update the User (password is handled in the `users` table)
            $user = User::updateOrCreate(
                ['id' => UserInfo::where('email', $userData['email'])->value('id')],
                ['password' => $userData['password']]
            );

            // Create or update UserInfo (linked to User by `id`)
            UserInfo::updateOrCreate(
                ['id' => $user->id],
                array_merge($userData['info'], ['email' => $userData['email']])
            );
        }
    }
}