<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class LocationControllerFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUserWithPermissions([PermissionEnum::LOCATIONS_VIEW]);
    }

    #[Test]
    public function search_filters_return_matching_location(): void
    {
        $matching = Location::factory()->create(['name' => 'LocSearchXYZ']);
        Location::factory()->count(2)->create();

        $response = $this->get(route('locations.index', ['search' => 'LocSearchXYZ']));

        $response->assertOk();
        $response->assertViewIs('location.index');

        $viewLocations = $response->viewData('locations');
        $this->assertEquals(1, $viewLocations->total());
        $this->assertEquals($matching->id, $viewLocations->items()[0]->id);
    }
}
