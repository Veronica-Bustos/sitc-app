<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'code' => fake()->unique()->bothify('LOC-####'),
            'type' => fake()->randomElement(['WAREHOUSE', 'SITE', 'OFFICE']),
            'address' => fake()->address(),
            'coordinates' => null,
            'responsible_user_id' => null,
            'parent_id' => null,
            'status' => fake()->randomElement(['ACTIVE', 'INACTIVE', 'CLOSED']),
            'start_date' => fake()->date(),
            'end_date' => null,
            'notes' => null,
        ];
    }
}
