<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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

        // Create users with different roles
        $this->createUsers();

        // Seed the verlofaanvragen table
        $this->call(VerlofaanvragenSeeder::class);
    }

    private function createUsers()
    {
        // Create a user with the werknemer role
        User::factory()->create([
            'voornaam' => 'Ahmad',
            'achternaam' => 'Mahouk',
            'telefoon' => '06123123123',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('Ahmad'),
            'role_id' => Role::where('roleName', 'werknemer')->first()->id,
        ]);

        // Create a user with the manager role
        User::factory()->create([
            'voornaam' => 'Damien',
            'achternaam' => 'Doe',
            'telefoon' => '06123456789',
            'email' => 'damien@gmail.com',
            'password' => Hash::make('Damien'),
            'role_id' => Role::where('roleName', 'manager')->first()->id,
        ]);

        // Create a user with the officemanagement role
        User::factory()->create([
            'voornaam' => 'Wassem',
            'achternaam' => 'Smith',
            'telefoon' => '06234567890',
            'email' => 'wassem@gmail.com',
            'password' => Hash::make('Wassem'),
            'role_id' => Role::where('roleName', 'officemanagement')->first()->id,
        ]);

        // Additional test user
        User::factory()->create([
            'voornaam' => 'Testen',
            'achternaam' => 'Smith',
            'telefoon' => '06234567890',
            'email' => 'testen@gmail.com',
            'password' => Hash::make('testen'),
            'role_id' => Role::where('roleName', 'officemanagement')->first()->id,
        ]);
    }
}
