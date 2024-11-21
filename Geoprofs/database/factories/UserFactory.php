<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'voornaam' => $this->faker->firstName,
            'tussennaam' => $this->faker->optional()->word,
            'achternaam' => $this->faker->lastName,
            'profielFoto' => 'profile_pictures/default_profile_photo.png', // Default profile picture
            'telefoon' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Default password
            'verlof_dagen' => 25, // Default leave days
            'failed_login_attempts' => 0,
            'role_id' => null,
            'team_id' => null,
        ];
    }
}