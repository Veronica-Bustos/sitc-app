<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Catalog\CategoryController
 */
final class CategoryControllerTest extends TestCase
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
        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('categories.index'));

        $response->assertOk();
        $response->assertViewIs('category.index');
        $response->assertViewHas('categories');

        $viewCategories = $response->viewData('categories');
        $this->assertEquals(3, $viewCategories->total());
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('categories.create'));

        $response->assertOk();
        $response->assertViewIs('category.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Catalog\CategoryController::class,
            'store',
            \App\Http\Requests\Catalog\StoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();
        $slug = fake()->slug();
        $is_active = fake()->boolean();

        $response = $this->post(route('categories.store'), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $categories = Category::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $categories);
        $category = $categories->first();

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('category.id', $category->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertViewIs('category.show');
        $response->assertViewHas('category', $category);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertOk();
        $response->assertViewIs('category.edit');
        $response->assertViewHas('category', $category);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Catalog\CategoryController::class,
            'update',
            \App\Http\Requests\Catalog\UpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $category = Category::factory()->create();
        $name = fake()->name();
        $slug = fake()->slug();
        $is_active = fake()->boolean();

        $response = $this->put(route('categories.update', $category), [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $category->refresh();

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('category.id', $category->id);

        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($is_active, $category->is_active);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));

        $this->assertSoftDeleted($category);
    }
}
