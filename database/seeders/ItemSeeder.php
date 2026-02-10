<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Location;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();

        if ($locations->isEmpty()) {
            $this->call(LocationSeeder::class);
            $locations = Location::all();
        }

        for ($i = 0; $i < 10; $i++) {
            Item::factory()->create([
                'current_location_id' => $locations->random()->id,
            ]);
        }

        $this->command->info('Seeded items: 10');
    }
}
