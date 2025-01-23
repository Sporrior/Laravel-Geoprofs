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
            'id' => null,
            'voornaam' => null,
            'achternaam' => null,
            'email' => null,
            'telefoon' => null,
            'verlof_dagen' => 20,
            'failed_login_attempts' => 0,
            'blocked_until' => null,
            'role_id' => 1,
            'team_id' => 1,
        ];
    }
}