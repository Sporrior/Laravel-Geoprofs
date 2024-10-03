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
        // Fetch existing user IDs and status IDs
        $userIds = User::pluck('id')->toArray();
        $typeIds = type::pluck('id')->toArray();

        // Insert dummy data into verlofaanvragens
        foreach ($userIds as $userId) {
            DB::table('verlofaanvragens')->insert([
                'verlof_reden' => 'Dummy reason for leave for user ' . $userId,
                'aanvraag_datum' => Carbon::now()->subDays(rand(1, 30)),
                'start_datum' => Carbon::now()->addDays(rand(1, 10)),
                'eind_datum' => Carbon::now()->addDays(rand(11, 20)),
                'verlof_soort' => $typeIds[array_rand($typeIds)],
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
