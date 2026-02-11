<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CategoryControllerFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUserWithPermissions([PermissionEnum::CATEGORIES_VIEW]);
    }

    #[Test]
    public function search_filters_return_matching_category(): void
    {
        $matching = Category::factory()->create(['name' => 'CatSearchX123']);
        Category::factory()->count(2)->create();

        $response = $this->get(route('categories.index', ['search' => 'CatSearchX123']));

        $response->assertOk();
        $response->assertViewIs('category.index');

        $viewCategories = $response->viewData('categories');
        $this->assertEquals(1, $viewCategories->total());
        $this->assertEquals($matching->id, $viewCategories->items()[0]->id);
    }
}
