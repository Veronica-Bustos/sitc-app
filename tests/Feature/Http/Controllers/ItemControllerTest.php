<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Inventory\ItemController
 */
final class ItemControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUserWithPermissions([
            PermissionEnum::ITEMS_VIEW,
            PermissionEnum::ITEMS_CREATE,
            PermissionEnum::ITEMS_EDIT,
            PermissionEnum::ITEMS_DELETE,
            PermissionEnum::ITEMS_HISTORY,
        ]);
    }

    #[Test]
    public function index_displays_view(): void
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('items.index'));

        $response->assertOk();
        $response->assertViewIs('item.index');
        $response->assertViewHas('items');

        $paginator = $response->viewData('items');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $paginator);
        $this->assertEquals($items->count(), $paginator->count());
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('items.create'));

        $response->assertOk();
        $response->assertViewIs('item.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Inventory\ItemController::class,
            'store',
            \App\Http\Requests\Inventory\StoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $code = fake()->word();
        $name = fake()->name();
        $category = Category::factory()->create();
        $status = fake()->word();
        $condition = fake()->word();
        $minimum_stock = fake()->numberBetween(-10000, 10000);

        $response = $this->post(route('items.store'), [
            'code' => $code,
            'name' => $name,
            'category_id' => $category->id,
            'status' => $status,
            'condition' => $condition,
            'minimum_stock' => $minimum_stock,
        ]);

        $items = Item::query()
            ->where('code', $code)
            ->where('name', $name)
            ->where('category_id', $category->id)
            ->where('status', $status)
            ->where('condition', $condition)
            ->where('minimum_stock', $minimum_stock)
            ->get();
        $this->assertCount(1, $items);
        $item = $items->first();

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('item.id', $item->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $item = Item::factory()->create();

        $response = $this->get(route('items.show', $item));

        $response->assertOk();
        $response->assertViewIs('item.show');
        $response->assertViewHas('item', $item);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $item = Item::factory()->create();

        $response = $this->get(route('items.edit', $item));

        $response->assertOk();
        $response->assertViewIs('item.edit');
        $response->assertViewHas('item', $item);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Inventory\ItemController::class,
            'update',
            \App\Http\Requests\Inventory\UpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $item = Item::factory()->create();
        $code = fake()->word();
        $name = fake()->name();
        $category = Category::factory()->create();
        $status = fake()->word();
        $condition = fake()->word();
        $minimum_stock = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('items.update', $item), [
            'code' => $code,
            'name' => $name,
            'category_id' => $category->id,
            'status' => $status,
            'condition' => $condition,
            'minimum_stock' => $minimum_stock,
        ]);

        $item->refresh();

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('item.id', $item->id);

        $this->assertEquals($code, $item->code);
        $this->assertEquals($name, $item->name);
        $this->assertEquals($category->id, $item->category_id);
        $this->assertEquals($status, $item->status);
        $this->assertEquals($condition, $item->condition);
        $this->assertEquals($minimum_stock, $item->minimum_stock);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $item = Item::factory()->create();

        $response = $this->delete(route('items.destroy', $item));

        $response->assertRedirect(route('items.index'));

        $this->assertSoftDeleted($item);
    }

    #[Test]
    public function history_displays_view(): void
    {
        $item = Item::factory()->create();

        $response = $this->get(route('items.history', $item));

        $response->assertOk();
        $response->assertViewIs('item.history');
        $response->assertViewHas('item', $item);
        $response->assertViewHas('movements');
    }

    #[Test]
    public function history_shows_movements_when_present(): void
    {
        $item = Item::factory()->create();

        // Create movements for the item (use movement_type values that the view expects)
        $movements = \Database\Factories\InventoryMovementFactory::new()->count(3)->for($item)->state(['movement_type' => 'transfer'])->create();

        $response = $this->get(route('items.history', $item));

        $response->assertOk();
        $response->assertViewIs('item.history');
        $response->assertViewHas('movements');

        // Ensure the rendered HTML contains data from one movement (notes or reference)
        $first = $movements->first();
        if ($first->notes) {
            $response->assertSee(e($first->notes));
        } elseif ($first->reference_document) {
            $response->assertSee(e($first->reference_document));
        } else {
            // fallback: check quantity
            $response->assertSee((string) $first->quantity);
        }
    }
}
