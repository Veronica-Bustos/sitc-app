<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some items
        $items = Item::query()->count() ? Item::all() : Item::factory()->count(3)->create();

        $locations = Location::all();
        $users = User::all();

        if ($locations->isEmpty()) {
            $this->call(LocationSeeder::class);
            $locations = Location::all();
        }

        if ($users->isEmpty()) {
            $this->call(AdminUserSeeder::class);
            $users = User::all();
        }

        foreach ($items as $item) {
            $userId = $users->random()->id;
            // Create a variety of movements for each item
            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'check_in',
                'quantity' => 1,
                'notes' => 'Ingreso inicial de inventario',
                'from_location_id' => null,
                'to_location_id' => $locations->random()->id,
                'user_id' => $userId,
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'transfer',
                'quantity' => 1,
                'notes' => 'Transferido a obra principal',
                'from_location_id' => $locations->random()->id,
                'to_location_id' => $locations->random()->id,
                'user_id' => $userId,
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'return',
                'quantity' => 1,
                'notes' => 'Retornado por mantenimiento',
                'from_location_id' => $locations->random()->id,
                'to_location_id' => $locations->random()->id,
                'user_id' => $userId,
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'check_out',
                'quantity' => 1,
                'notes' => 'Salida para uso en obra',
                'from_location_id' => $locations->random()->id,
                'to_location_id' => null,
                'user_id' => $userId,
            ]);
        }

        $this->command->info('Seeded item history for ' . count($items) . ' items.');
    }
}
