<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreRequest as MaintenanceStoreRequest;
use App\Http\Requests\Maintenance\UpdateRequest as MaintenanceUpdateRequest;
use App\Models\MaintenanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $maintenanceRecords = MaintenanceRecord::all();

        return view('maintenanceRecord.index', [
            'maintenanceRecords' => $maintenanceRecords,
        ]);
    }

    public function create(Request $request): View
    {
        return view('maintenanceRecord.create');
    }

    public function store(MaintenanceStoreRequest $request): RedirectResponse
    {
        $maintenanceRecord = MaintenanceRecord::create($request->validated());

        $request->session()->flash('maintenanceRecord.id', $maintenanceRecord->id);

        return redirect()->route('maintenance-records.index');
    }

    public function show(Request $request, MaintenanceRecord $maintenanceRecord): View
    {
        return view('maintenanceRecord.show', [
            'maintenanceRecord' => $maintenanceRecord,
        ]);
    }

    public function edit(Request $request, MaintenanceRecord $maintenanceRecord): View
    {
        return view('maintenanceRecord.edit', [
            'maintenanceRecord' => $maintenanceRecord,
        ]);
    }

    public function update(MaintenanceUpdateRequest $request, MaintenanceRecord $maintenanceRecord): RedirectResponse
    {
        $maintenanceRecord->update($request->validated());

        $request->session()->flash('maintenanceRecord.id', $maintenanceRecord->id);

        return redirect()->route('maintenance-records.index');
    }

    public function destroy(Request $request, MaintenanceRecord $maintenanceRecord): RedirectResponse
    {
        $maintenanceRecord->delete();

        return redirect()->route('maintenance-records.index');
    }
}
