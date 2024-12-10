<?php

namespace Database\Factories;

use App\Models\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInfoFactory extends Factory
{
    protected $model = UserInfo::class;

    public function definition()
    {
        return [
            'id' => null, // Ensure to set this manually to match the User
            'voornaam' => $this->faker->firstName,
            'achternaam' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefoon' => $this->faker->phoneNumber,
            'verlof_dagen' => 20,
            'failed_login_attempts' => 0,
            'blocked_until' => null,
            'role_id' => 1,
            'team_id' => 1,
        ];
    }
}