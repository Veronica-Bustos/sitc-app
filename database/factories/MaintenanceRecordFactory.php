<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'request_date' => fake()->date(),
            'intervention_date' => null,
            'completion_date' => null,
            'type' => fake()->randomElement(['PREVENTIVE', 'CORRECTIVE', 'INSPECTION', 'CALIBRATION']),
            'status' => fake()->randomElement(['PENDING', 'IN_PROGRESS', 'COMPLETED', 'CANCELLED']),
            'priority' => fake()->randomElement(['LOW', 'MEDIUM', 'HIGH', 'CRITICAL']),
            'description' => fake()->sentence(),
            'diagnosis' => null,
            'actions_taken' => null,
            'parts_replaced' => null,
            'cost' => fake()->optional()->randomFloat(2, 50, 10000),
            'technician_id' => null,
            'requester_id' => null,
            'next_maintenance_date' => null,
        ];
    }
}
