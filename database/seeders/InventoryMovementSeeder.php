<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventoryMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();
        $items = Item::all();
        $users = User::all();

        if ($locations->isEmpty()) {
            $this->call(LocationSeeder::class);
            $locations = Location::all();
        }

        if ($items->isEmpty()) {
            $this->call(ItemSeeder::class);
            $items = Item::all();
        }

        if ($users->isEmpty()) {
            $this->call(AdminUserSeeder::class);
            $users = User::all();
        }

        for ($i = 0; $i < 5; $i++) {
            InventoryMovement::factory()->create([
                'item_id' => $items->random()->id,
                'from_location_id' => $locations->random()->id,
                'to_location_id' => $locations->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }

        $this->command->info('Seeded inventory movements: 5');
    }
}
