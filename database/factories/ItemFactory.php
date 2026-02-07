<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('ITM-####-??'),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'category_id' => Category::factory(),
            'current_location_id' => Location::factory(),
            'status' => fake()->randomElement(['AVAILABLE', 'IN_USE', 'IN_REPAIR', 'DAMAGED', 'LOST', 'RETIRED']),
            'condition' => fake()->randomElement(['EXCELLENT', 'GOOD', 'FAIR', 'POOR']),
            'purchase_date' => fake()->date(),
            'purchase_price' => fake()->randomFloat(2, 10, 50000),
            'current_value' => fake()->randomFloat(2, 5, 40000),
            'serial_number' => fake()->unique()->bothify('SN-########'),
            'brand' => fake()->company(),
            'model' => fake()->bothify('Model-??-####'),
            'supplier' => fake()->company(),
            'warranty_expiry' => fake()->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
            'barcode' => fake()->ean13(),
            'qr_code' => null,
            'minimum_stock' => fake()->numberBetween(0, 50),
            'unit_of_measure' => fake()->randomElement(['UNIT', 'KG', 'LT', 'MT', 'BOX']),
            'weight_kg' => fake()->randomFloat(2, 0.1, 500),
            'dimensions' => null,
        ];
    }
}
