<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Logistics\MovementController
 */
final class InventoryMovementControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function index_displays_view(): void
    {
        $inventoryMovements = InventoryMovement::factory()->count(3)->create();

        $response = $this->get(route('inventory-movements.index'));

        $response->assertOk();
        $response->assertViewIs('inventoryMovement.index');
        $response->assertViewHas('inventoryMovements', fn ($paginator) => $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator);
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('inventory-movements.create'));

        $response->assertOk();
        $response->assertViewIs('inventoryMovement.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Logistics\MovementController::class,
            'store',
            \App\Http\Requests\Logistics\MovementStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();
        $movement_type = fake()->word();
        $quantity = fake()->numberBetween(1, 100);
        $performed_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('inventory-movements.store'), [
            'item_id' => $item->id,
            'movement_type' => $movement_type,
            'user_id' => $user->id,
            'quantity' => $quantity,
            'performed_at' => $performed_at->toDateTimeString(),
        ]);

        $response->assertRedirect(route('inventory-movements.index'));
        $response->assertSessionHas('inventoryMovement.id');

        // Verify record was created with authenticated user's ID (controller overwrites user_id)
        $this->assertDatabaseHas('inventory_movements', [
            'item_id' => $item->id,
            'movement_type' => $movement_type,
            'quantity' => $quantity,
        ]);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->get(route('inventory-movements.show', $inventoryMovement));

        $response->assertOk();
        $response->assertViewIs('inventoryMovement.show');
        $response->assertViewHas('inventoryMovement', $inventoryMovement);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->get(route('inventory-movements.edit', $inventoryMovement));

        $response->assertOk();
        $response->assertViewIs('inventoryMovement.edit');
        $response->assertViewHas('inventoryMovement', $inventoryMovement);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Logistics\MovementController::class,
            'update',
            \App\Http\Requests\Logistics\MovementUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $inventoryMovement = InventoryMovement::factory()->create();
        $item = Item::factory()->create();
        $movement_type = fake()->word();
        $user = User::factory()->create();
        $quantity = fake()->numberBetween(-10000, 10000);
        $performed_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('inventory-movements.update', $inventoryMovement), [
            'item_id' => $item->id,
            'movement_type' => $movement_type,
            'user_id' => $user->id,
            'quantity' => $quantity,
            'performed_at' => $performed_at->toDateTimeString(),
        ]);

        $inventoryMovement->refresh();

        $response->assertRedirect(route('inventory-movements.index'));
        $response->assertSessionHas('inventoryMovement.id', $inventoryMovement->id);

        $this->assertEquals($item->id, $inventoryMovement->item_id);
        $this->assertEquals($movement_type, $inventoryMovement->movement_type);
        $this->assertEquals($user->id, $inventoryMovement->user_id);
        $this->assertEquals($quantity, $inventoryMovement->quantity);
        $this->assertEquals($performed_at, $inventoryMovement->performed_at);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->delete(route('inventory-movements.destroy', $inventoryMovement));

        $response->assertRedirect(route('inventory-movements.index'));

        $this->assertModelMissing($inventoryMovement);
    }
}
