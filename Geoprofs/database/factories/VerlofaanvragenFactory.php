<?php

namespace Database\Factories;

use App\Models\Verlofaanvragen;
use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerlofaanvragenFactory extends Factory
{
    protected $model = Verlofaanvragen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'verlof_reden' => $this->faker->sentence(),
            'aanvraag_datum' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'start_datum' => $this->faker->dateTimeBetween('now', '+1 week'),
            'eind_datum' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'status' => $this->faker->randomElement([null, 1, 0]), // null = pending, 1 = approved, 0 = rejected
            'verlof_soort' => Type::factory(), // Link to TypeFactory
            'user_id' => User::factory(), // Link to UserFactory
            'weigerreden' => $this->faker->optional()->sentence(), // Optional rejection reason
        ];
    }
}