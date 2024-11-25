<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Type;
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
        $typeIds = Type::pluck('id')->toArray();

        // Insert empty or null data into verlofaanvragen
        foreach ($userIds as $userId) {

            // Inserting a sick day on November 11
            DB::table('verlofaanvragen')->insert([
                'verlof_reden' => 'Ziek', // Reason for sick leave
                'aanvraag_datum' => Carbon::now()->format('Y-m-d'),
                'start_datum' => '2024-11-11',
                'eind_datum' => '2024-11-11',
                'verlof_soort' => $typeIds[array_rand($typeIds)], // Randomly assign a type if needed
                'user_id' => $userId,
                'status' => 1, // Assuming 1 means approved
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
