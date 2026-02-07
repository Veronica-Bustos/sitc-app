<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
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

        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function index_displays_view(): void
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('items.index'));

        $response->assertOk();
        $response->assertViewIs('item.index');
        $response->assertViewHas('items', $items);
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
}
