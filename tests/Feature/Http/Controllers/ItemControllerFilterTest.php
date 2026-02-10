<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ItemControllerFilterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function search_filters_return_matching_item(): void
    {
        $matching = Item::factory()->create(['name' => 'UniqueSearchItemName123']);
        Item::factory()->count(2)->create();

        $response = $this->get(route('items.index', ['search' => 'UniqueSearchItemName123']));

        $response->assertOk();
        $response->assertViewIs('item.index');

        $viewItems = $response->viewData('items');
        $this->assertEquals(1, $viewItems->total());
        $this->assertEquals($matching->id, $viewItems->items()[0]->id);
    }
}
