<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'voornaam' => $this->faker->firstName,
        //     'tussennaam' => $this->faker->optional()->word, // Optional middle name
        //     'achternaam' => $this->faker->lastName,
        //     'profielFoto' => 'profile_pictures/default_profile_photo.png', // Default profile photo
        //     'telefoon' => $this->faker->phoneNumber,
        //     'email' => $this->faker->unique()->safeEmail,
        //     'email_verified_at' => now(),
        //     'password' => static::$password ??= Hash::make('password'), // Default password
        //     'remember_token' => Str::random(10),
        //     'verlof_dagen' => 25, // Default leave days
        //     'role_id' => Role::factory(), // Creates a Role and assigns it
        //     'team_id' => Team::factory(), // Creates a Team and assigns it
        // ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
