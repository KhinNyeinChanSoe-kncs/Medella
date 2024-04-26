<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => "admin",
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('abcd1234'),
            'phone' => '09789978035',
            'avatar' => 'http://127.0.0.1:8000/images/image0.jpg',
            'address' => $this->faker->address,
            'city'=> $this->faker->city,
            'gender' => $this->faker->gender,
            'role_id' => 1,
            'department_id'=> 1
        ];
    }
}
