<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Logistics\LocationController
 */
final class LocationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUserWithPermissions([
            PermissionEnum::LOCATIONS_VIEW,
            PermissionEnum::LOCATIONS_CREATE,
            PermissionEnum::LOCATIONS_EDIT,
            PermissionEnum::LOCATIONS_DELETE,
        ]);
    }

    #[Test]
    public function index_displays_view(): void
    {
        $locations = Location::factory()->count(3)->create();

        $response = $this->get(route('locations.index'));

        $response->assertOk();
        $response->assertViewIs('location.index');
        $response->assertViewHas('locations');

        $viewLocations = $response->viewData('locations');
        $this->assertEquals(3, $viewLocations->total());
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('locations.create'));

        $response->assertOk();
        $response->assertViewIs('location.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Logistics\LocationController::class,
            'store',
            \App\Http\Requests\Logistics\LocationStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();
        $code = fake()->word();
        $type = fake()->word();
        $status = fake()->word();

        $response = $this->post(route('locations.store'), [
            'name' => $name,
            'code' => $code,
            'type' => $type,
            'status' => $status,
        ]);

        $locations = Location::query()
            ->where('name', $name)
            ->where('code', $code)
            ->where('type', $type)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $locations);
        $location = $locations->first();

        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('location.id', $location->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $location = Location::factory()->create();

        $response = $this->get(route('locations.show', $location));

        $response->assertOk();
        $response->assertViewIs('location.show');
        $response->assertViewHas('location', $location);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $location = Location::factory()->create();

        $response = $this->get(route('locations.edit', $location));

        $response->assertOk();
        $response->assertViewIs('location.edit');
        $response->assertViewHas('location', $location);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Logistics\LocationController::class,
            'update',
            \App\Http\Requests\Logistics\LocationUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $location = Location::factory()->create();
        $name = fake()->name();
        $code = fake()->word();
        $type = fake()->word();
        $status = fake()->word();

        $response = $this->put(route('locations.update', $location), [
            'name' => $name,
            'code' => $code,
            'type' => $type,
            'status' => $status,
        ]);

        $location->refresh();

        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('location.id', $location->id);

        $this->assertEquals($name, $location->name);
        $this->assertEquals($code, $location->code);
        $this->assertEquals($type, $location->type);
        $this->assertEquals($status, $location->status);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $location = Location::factory()->create();

        $response = $this->delete(route('locations.destroy', $location));

        $response->assertRedirect(route('locations.index'));

        $this->assertSoftDeleted($location);
    }
}
