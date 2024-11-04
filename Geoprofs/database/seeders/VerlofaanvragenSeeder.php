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
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = User::pluck('id')->toArray();
        $typeIds = Type::pluck('id')->toArray();
        $statusCycle = [null, 1, 1, 0];
        $cycleLength = count($statusCycle);
        $counter = 0;

        foreach ($userIds as $userId) {
            $startDate = Carbon::now()->addDays(rand(1, 10));
            $endDate = (clone $startDate)->addDays(rand(5, 15));

            DB::table('verlofaanvragens')->insert([
                'verlof_reden' => $faker->sentence(rand(3, 6)),
                'aanvraag_datum' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'start_datum' => $startDate->format('Y-m-d'),
                'eind_datum' => $endDate->format('Y-m-d'),
                'verlof_soort' => $typeIds[array_rand($typeIds)],
                'user_id' => $userId,
                'status' => $statusCycle[$counter % $cycleLength],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter++;
        }
    }
}
