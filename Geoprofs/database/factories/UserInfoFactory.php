<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserInfoFactory extends Factory
{
    public function definition()
    {
        return [
            'voornaam' => $this->faker->firstName,
            'tussennaam' => $this->faker->optional()->word,
            'achternaam' => $this->faker->lastName,
            'profielFoto' => 'profile_pictures/default_profile_photo.png',
            'telefoon' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'verlof_dagen' => 25,
            'failed_login_attempts' => 0,
            'role_id' => null,
            'team_id' => null,
        ];
    }
}