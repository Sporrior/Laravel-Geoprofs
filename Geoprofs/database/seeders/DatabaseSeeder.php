<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role; // Import the Role model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if not already created
        Role::firstOrCreate(['roleName' => 'werknemer']);
        Role::firstOrCreate(['roleName' => 'manager']);
        Role::firstOrCreate(['roleName' => 'office management']);

        // Create a user with a role
        User::factory()->create([
            'voorNaam' => 'Ahmad',
            'achterNaam' => 'Mahouk',
            'telefoon' => '06123123123',
            'email' => 'Ahmad@gmail.com',
            'password' => Hash::make('Ahmad'),
            'role_id' => Role::where('roleName', 'werknemer')->first()->id, // Assign a role
        ]);
    }
}
