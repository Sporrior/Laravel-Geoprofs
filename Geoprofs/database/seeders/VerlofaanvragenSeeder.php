<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Status; // Assuming this is the correct model name
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerlofaanvragenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing user IDs and status IDs
        $userIds = User::pluck('id')->toArray(); // Fetch all user IDs
        $statusIds = Status::pluck('id')->toArray(); // Fetch all status IDs

        // Insert dummy data into verlofaanvragens
        foreach ($userIds as $userId) {
            DB::table('verlofaanvragens')->insert([
                'verlof_reden' => 'Dummy reason for leave for user ' . $userId,
                'aanvraag_datum' => Carbon::now()->subDays(rand(1, 30)), // Random date in the past month
                'start_datum' => Carbon::now()->addDays(rand(1, 10)), // Random start date in the next 10 days
                'eind_datum' => Carbon::now()->addDays(rand(11, 20)), // Random end date after the start date
                'verlof_soort' => $statusIds[array_rand($statusIds)], // Randomly pick a status ID
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
