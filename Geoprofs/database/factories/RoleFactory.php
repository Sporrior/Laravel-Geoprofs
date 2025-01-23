<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_name' => $this->faker->unique()->jobTitle,
        ];
    }

    /**
     * Define a state for the 'werknemer' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function werknemer()
    {
        return $this->state([
            'role_name' => 'werknemer',
        ]);
    }

    /**
     * Define a state for the 'admin' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state([
            'role_name' => 'admin',
        ]);
    }
}