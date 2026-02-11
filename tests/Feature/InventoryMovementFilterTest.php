<?php

use App\Enums\MovementTypeEnum;
use App\Enums\PermissionEnum;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;

it('filters movements by date range', function () {
    $user = $this->actingAsUserWithPermissions([PermissionEnum::MOVEMENTS_VIEW]);
    $item = Item::factory()->create();
    $location = Location::factory()->create();

    // Create movements with specific dates
    $movementInRange = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-02-05 10:00:00', // Within range
    ]);

    $movementOutOfRange = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-01-15 10:00:00', // Outside range (before)
    ]);

    $movementAfterRange = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-02-20 10:00:00', // Outside range (after)
    ]);

    $response = $this->get(route('inventory-movements.index', [
        'date_from' => '2026-02-02',
        'date_to' => '2026-02-12',
    ]));

    $response->assertOk();
    $response->assertViewHas('inventoryMovements', function ($movements) use ($movementInRange, $movementOutOfRange, $movementAfterRange) {
        $ids = $movements->pluck('id')->toArray();

        return in_array($movementInRange->id, $ids)
            && ! in_array($movementOutOfRange->id, $ids)
            && ! in_array($movementAfterRange->id, $ids);
    });
});

it('filters movements by date_from only', function () {
    $user = $this->actingAsUserWithPermissions([PermissionEnum::MOVEMENTS_VIEW]);
    $item = Item::factory()->create();
    $location = Location::factory()->create();

    $beforeMovement = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-01-15 10:00:00',
    ]);

    $afterMovement = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-02-10 10:00:00',
    ]);

    $response = $this->get(route('inventory-movements.index', [
        'date_from' => '2026-02-01',
    ]));

    $response->assertOk();
    $response->assertViewHas('inventoryMovements', function ($movements) use ($beforeMovement, $afterMovement) {
        $ids = $movements->pluck('id')->toArray();

        return ! in_array($beforeMovement->id, $ids)
            && in_array($afterMovement->id, $ids);
    });
});

it('filters movements by date_to only', function () {
    $user = $this->actingAsUserWithPermissions([PermissionEnum::MOVEMENTS_VIEW]);
    $item = Item::factory()->create();
    $location = Location::factory()->create();

    $beforeMovement = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-01-15 10:00:00',
    ]);

    $afterMovement = InventoryMovement::factory()->create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'from_location_id' => $location->id,
        'movement_type' => MovementTypeEnum::TRANSFER->value,
        'performed_at' => '2026-02-20 10:00:00',
    ]);

    $response = $this->get(route('inventory-movements.index', [
        'date_to' => '2026-02-01',
    ]));

    $response->assertOk();
    $response->assertViewHas('inventoryMovements', function ($movements) use ($beforeMovement, $afterMovement) {
        $ids = $movements->pluck('id')->toArray();

        return in_array($beforeMovement->id, $ids)
            && ! in_array($afterMovement->id, $ids);
    });
});
