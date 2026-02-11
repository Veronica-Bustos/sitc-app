<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Item;
use App\Models\MaintenanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Maintenance\MaintenanceController
 */
final class MaintenanceRecordControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUserWithPermissions([
            PermissionEnum::MAINTENANCE_VIEW,
            PermissionEnum::MAINTENANCE_CREATE,
            PermissionEnum::MAINTENANCE_EDIT,
            PermissionEnum::MAINTENANCE_DELETE,
        ]);
    }

    #[Test]
    public function index_displays_view(): void
    {
        $maintenanceRecords = MaintenanceRecord::factory()->count(3)->create();

        $response = $this->get(route('maintenance-records.index'));

        $response->assertOk();
        $response->assertViewIs('maintenanceRecord.index');
        $response->assertViewHas('maintenanceRecords', fn($paginator) => $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator);
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('maintenance-records.create'));

        $response->assertOk();
        $response->assertViewIs('maintenanceRecord.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Maintenance\MaintenanceController::class,
            'store',
            \App\Http\Requests\Maintenance\StoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $item = Item::factory()->create();
        $request_date = Carbon::parse(fake()->date());
        $type = fake()->word();
        $status = fake()->word();
        $priority = fake()->lexify('??????????');
        $description = fake()->text();

        $response = $this->post(route('maintenance-records.store'), [
            'item_id' => $item->id,
            'request_date' => $request_date->toDateString(),
            'type' => $type,
            'status' => $status,
            'priority' => $priority,
            'description' => $description,
        ]);

        $maintenanceRecords = MaintenanceRecord::query()
            ->where('item_id', $item->id)
            ->where('request_date', $request_date)
            ->where('type', $type)
            ->where('status', $status)
            ->where('priority', $priority)
            ->where('description', $description)
            ->get();
        $this->assertCount(1, $maintenanceRecords);
        $maintenanceRecord = $maintenanceRecords->first();

        $response->assertRedirect(route('maintenance-records.index'));
        $response->assertSessionHas('maintenanceRecord.id', $maintenanceRecord->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $maintenanceRecord = MaintenanceRecord::factory()->create();

        $response = $this->get(route('maintenance-records.show', $maintenanceRecord));

        $response->assertOk();
        $response->assertViewIs('maintenanceRecord.show');
        $response->assertViewHas('maintenanceRecord', $maintenanceRecord);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $maintenanceRecord = MaintenanceRecord::factory()->create();

        $response = $this->get(route('maintenance-records.edit', $maintenanceRecord));

        $response->assertOk();
        $response->assertViewIs('maintenanceRecord.edit');
        $response->assertViewHas('maintenanceRecord', $maintenanceRecord);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Maintenance\MaintenanceController::class,
            'update',
            \App\Http\Requests\Maintenance\UpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $maintenanceRecord = MaintenanceRecord::factory()->create();
        $item = Item::factory()->create();
        $request_date = Carbon::parse(fake()->date());
        $type = fake()->word();
        $status = fake()->word();
        $priority = fake()->lexify('??????????');
        $description = fake()->text();

        $response = $this->put(route('maintenance-records.update', $maintenanceRecord), [
            'item_id' => $item->id,
            'request_date' => $request_date->toDateString(),
            'type' => $type,
            'status' => $status,
            'priority' => $priority,
            'description' => $description,
        ]);

        $maintenanceRecord->refresh();

        $response->assertRedirect(route('maintenance-records.index'));
        $response->assertSessionHas('maintenanceRecord.id', $maintenanceRecord->id);

        $this->assertEquals($item->id, $maintenanceRecord->item_id);
        $this->assertEquals($request_date, $maintenanceRecord->request_date);
        $this->assertEquals($type, $maintenanceRecord->type);
        $this->assertEquals($status, $maintenanceRecord->status);
        $this->assertEquals($priority, $maintenanceRecord->priority);
        $this->assertEquals($description, $maintenanceRecord->description);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $maintenanceRecord = MaintenanceRecord::factory()->create();

        $response = $this->delete(route('maintenance-records.destroy', $maintenanceRecord));

        $response->assertRedirect(route('maintenance-records.index'));

        $this->assertSoftDeleted($maintenanceRecord);
    }
}
