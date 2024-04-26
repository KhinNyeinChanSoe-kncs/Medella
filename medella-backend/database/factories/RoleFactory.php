<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [[
            'id' => 1,
            'name' => 'admin',
            'description' => $this->faker->text(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'id' => 2,
            'name' => 'medical_staff',
            'description' => $this->faker->text(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'id' => 3,
            'name' => 'doctor',
            'description' => $this->faker->text(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'id' => 4,
            'name' => 'patient',
            'description' => $this->faker->text(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ]];
    }
}
