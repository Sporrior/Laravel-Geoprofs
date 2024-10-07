<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\type;
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
        // Fetch existing user IDs and type IDs
        $userIds = User::pluck('id')->toArray();
        $typeIds = type::pluck('id')->toArray();

        // Prepare a counter to ensure we control the status distribution
        $statusCycle = [null, 1, 1, 0]; // Sequence: null, 1, 1, 0
        $counter = 0; // Counter to track the current index in the status cycle

        // Insert dummy data into verlofaanvragens
        foreach ($userIds as $userId) {
            DB::table('verlofaanvragens')->insert([
                'verlof_reden' => 'Dummy reason for leave for user ' . $userId,
                'aanvraag_datum' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'start_datum' => Carbon::now()->addDays(rand(1, 10))->format('Y-m-d'),
                'eind_datum' => Carbon::now()->addDays(rand(11, 20))->format('Y-m-d'),
                'verlof_soort' => $typeIds[array_rand($typeIds)],
                'user_id' => $userId,
                'status' => $statusCycle[$counter % 4], // Use the current status from the cycle
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Increment the counter for the next status
            $counter++;
        }
    }
}
