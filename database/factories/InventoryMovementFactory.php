<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $userId = User::query()->inRandomOrder()->value('id');

        return [
            'item_id' => Item::factory(),
            'from_location_id' => Location::factory(),
            'to_location_id' => Location::factory(),
            'movement_type' => fake()->randomElement(['ASSIGNMENT', 'RETURN', 'TRANSFER', 'DISPOSAL', 'RECEPTION']),
            'user_id' => $userId ?? User::factory(),
            'quantity' => fake()->numberBetween(1, 100),
            'notes' => fake()->optional()->sentence(),
            'reason' => fake()->optional()->word(),
            'reference_document' => fake()->optional()->bothify('DOC-####'),
            'performed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
