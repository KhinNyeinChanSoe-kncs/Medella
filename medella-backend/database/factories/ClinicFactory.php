<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            [
                'name' => 'Paris',
                'phone' => $this->faker->unique()->phoneNumber,
                'email' => $this->faker->unique()->email,
                'description' => $this->faker->word(5),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'London',
                'phone' => $this->faker->unique()->phoneNumber,
                'email' => $this->faker->unique()->email,
                'description' => $this->faker->word(5),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Tokyo',
                'phone' => $this->faker->unique()->phoneNumber,
                'email' => $this->faker->unique()->email,
                'description' => $this->faker->word(5),
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Syndney',
                'phone' => $this->faker->unique()->phoneNumber,
                'email' => $this->faker->unique()->email,
                'description' => $this->faker->word(5),
                'created_at' => Carbon::now(),
            ]
        ];
    }
}
