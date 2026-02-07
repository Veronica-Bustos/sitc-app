<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'file_path' => 'attachments/'.fake()->uuid().'.pdf',
            'file_name' => fake()->uuid().'.pdf',
            'original_name' => fake()->word().'.pdf',
            'mime_type' => fake()->randomElement(['application/pdf', 'image/jpeg', 'image/png']),
            'size' => fake()->numberBetween(1024, 10485760),
            'disk' => 's3',
            'description' => fake()->optional()->sentence(),
            'is_featured' => false,
            'order' => 0,
            'uploader_id' => null,
            'attachable_id' => Item::factory(),
            'attachable_type' => Item::class,
        ];
    }
}
