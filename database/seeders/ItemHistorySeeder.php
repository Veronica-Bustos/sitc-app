<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Item;
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

        foreach ($items as $item) {
            // Create a variety of movements for each item
            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'check_in',
                'quantity' => 1,
                'notes' => 'Ingreso inicial de inventario',
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'transfer',
                'quantity' => 1,
                'notes' => 'Transferido a obra principal',
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'return',
                'quantity' => 1,
                'notes' => 'Retornado por mantenimiento',
            ]);

            InventoryMovement::factory()->create([
                'item_id' => $item->id,
                'movement_type' => 'check_out',
                'quantity' => 1,
                'notes' => 'Salida para uso en obra',
            ]);
        }

        $this->command->info('Seeded item history for ' . count($items) . ' items.');
    }
}
