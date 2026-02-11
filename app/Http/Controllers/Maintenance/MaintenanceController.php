<?php

namespace App\Http\Controllers\Maintenance;

use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Enums\MaintenanceTypeEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreRequest as MaintenanceStoreRequest;
use App\Http\Requests\Maintenance\UpdateRequest as MaintenanceUpdateRequest;
use App\Models\Item;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MaintenanceRecord::class, 'maintenance_record');
    }

    public function index(Request $request, \App\Filters\MaintenanceFilter $filters): View
    {
        $query = MaintenanceRecord::query()
            ->with(['item', 'technician', 'requester']);

        $maintenanceRecords = $query->filter($filters)
            ->paginate(20)
            ->withQueryString();

        // Get users with TECNICO role for technician filter
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::TECNICO->value);
        })->orWhereHas('permissions', function ($query) {
            $query->where('name', 'maintenance.edit');
        })->orderBy('name')->get();

        return view('maintenanceRecord.index', [
            'maintenanceRecords' => $maintenanceRecords,
            'items' => Item::orderBy('name')->get(),
            'technicians' => $technicians,
            'statuses' => MaintenanceStatusEnum::options(),
            'types' => MaintenanceTypeEnum::options(),
            'priorities' => MaintenancePriorityEnum::options(),
        ]);
    }

    public function create(Request $request): View
    {
        $preselectedItem = $request->query('item')
            ? Item::find($request->query('item'))
            : null;

        // Get users with TECNICO role
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::TECNICO->value);
        })->orWhereHas('permissions', function ($query) {
            $query->where('name', 'maintenance.edit');
        })->orderBy('name')->get();

        return view('maintenanceRecord.create', [
            'items' => Item::orderBy('name')->get(),
            'technicians' => $technicians,
            'types' => MaintenanceTypeEnum::options(),
            'statuses' => MaintenanceStatusEnum::options(),
            'priorities' => MaintenancePriorityEnum::options(),
            'preselectedItem' => $preselectedItem,
        ]);
    }

    public function store(MaintenanceStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['requester_id'] = auth()->id();

        // Set default status if not provided
        if (empty($data['status'])) {
            $data['status'] = MaintenanceStatusEnum::PENDING->value;
        }

        $maintenanceRecord = MaintenanceRecord::create($data);

        // Update item status if maintenance is being started
        if ($data['status'] === MaintenanceStatusEnum::IN_PROGRESS->value && isset($data['item_id'])) {
            $item = Item::find($data['item_id']);
            if ($item) {
                $item->update(['status' => 'in_repair']);
            }
        }

        $request->session()->flash('maintenanceRecord.id', $maintenanceRecord->id);

        return redirect()->route('maintenance-records.index')
            ->with('success', __('Maintenance record created successfully'));
    }

    public function show(Request $request, MaintenanceRecord $maintenanceRecord): View
    {
        $maintenanceRecord->load(['item', 'technician', 'requester', 'attachments']);

        return view('maintenanceRecord.show', [
            'maintenanceRecord' => $maintenanceRecord,
        ]);
    }

    public function edit(Request $request, MaintenanceRecord $maintenanceRecord): View
    {
        $maintenanceRecord->load(['item', 'technician', 'requester']);

        // Get users with TECNICO role
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::TECNICO->value);
        })->orWhereHas('permissions', function ($query) {
            $query->where('name', 'maintenance.edit');
        })->orderBy('name')->get();

        return view('maintenanceRecord.edit', [
            'maintenanceRecord' => $maintenanceRecord,
            'items' => Item::orderBy('name')->get(),
            'technicians' => $technicians,
            'types' => MaintenanceTypeEnum::options(),
            'statuses' => MaintenanceStatusEnum::options(),
            'priorities' => MaintenancePriorityEnum::options(),
        ]);
    }

    public function update(MaintenanceUpdateRequest $request, MaintenanceRecord $maintenanceRecord): RedirectResponse
    {
        $oldStatus = $maintenanceRecord->status;
        $data = $request->validated();

        $maintenanceRecord->update($data);

        // Update item status based on maintenance status change
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $item = Item::find($maintenanceRecord->item_id);
            if ($item) {
                if ($data['status'] === MaintenanceStatusEnum::IN_PROGRESS->value) {
                    $item->update(['status' => 'in_repair']);
                } elseif ($data['status'] === MaintenanceStatusEnum::COMPLETED->value) {
                    $item->update(['status' => 'available']);
                }
            }
        }

        $request->session()->flash('maintenanceRecord.id', $maintenanceRecord->id);

        return redirect()->route('maintenance-records.index')
            ->with('success', __('Maintenance record updated successfully'));
    }

    public function destroy(Request $request, MaintenanceRecord $maintenanceRecord): RedirectResponse
    {
        $maintenanceRecord->delete();

        return redirect()->route('maintenance-records.index')
            ->with('success', __('Maintenance record deleted successfully'));
    }
}
