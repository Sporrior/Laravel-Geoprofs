<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class VerlofaanvragenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch existing user IDs and type IDs
        $userIds = User::pluck('id')->toArray();
        $typeIds = Type::pluck('id')->toArray();

        // Define a cycle for status distribution with flexible, customizable values
        $statusCycle = [null, 1, 1, 0];
        $cycleLength = count($statusCycle);
        $counter = 0;

        // Insert dummy data into verlofaanvragens
        foreach ($userIds as $userId) {
            $startDate = Carbon::now()->addDays(rand(1, 10));
            $endDate = (clone $startDate)->addDays(rand(5, 15)); // Ensuring end date is after start date

            DB::table('verlofaanvragens')->insert([
                'verlof_reden' => $faker->sentence(rand(3, 6)), // More natural-sounding reason
                'aanvraag_datum' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'start_datum' => $startDate->format('Y-m-d'),
                'eind_datum' => $endDate->format('Y-m-d'),
                'verlof_soort' => $typeIds[array_rand($typeIds)], // Randomly pick a leave type
                'user_id' => $userId,
                'status' => $statusCycle[$counter % $cycleLength], // Cycle through statuses
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter++;
        }
    }
}
