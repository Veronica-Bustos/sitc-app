<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logistics\MovementStoreRequest;
use App\Http\Requests\Logistics\MovementUpdateRequest;
use App\Models\InventoryMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovementController extends Controller
{
    public function index(Request $request): View
    {
        $inventoryMovements = InventoryMovement::all();

        return view('inventoryMovement.index', [
            'inventoryMovements' => $inventoryMovements,
        ]);
    }

    public function create(Request $request): View
    {
        return view('inventoryMovement.create');
    }

    public function store(MovementStoreRequest $request): RedirectResponse
    {
        $inventoryMovement = InventoryMovement::create($request->validated());

        $request->session()->flash('inventoryMovement.id', $inventoryMovement->id);

        return redirect()->route('inventory-movements.index');
    }

    public function show(Request $request, InventoryMovement $inventoryMovement): View
    {
        return view('inventoryMovement.show', [
            'inventoryMovement' => $inventoryMovement,
        ]);
    }

    public function edit(Request $request, InventoryMovement $inventoryMovement): View
    {
        return view('inventoryMovement.edit', [
            'inventoryMovement' => $inventoryMovement,
        ]);
    }

    public function update(MovementUpdateRequest $request, InventoryMovement $inventoryMovement): RedirectResponse
    {
        $inventoryMovement->update($request->validated());

        $request->session()->flash('inventoryMovement.id', $inventoryMovement->id);

        return redirect()->route('inventory-movements.index');
    }

    public function destroy(Request $request, InventoryMovement $inventoryMovement): RedirectResponse
    {
        $inventoryMovement->delete();

        return redirect()->route('inventory-movements.index');
    }
}
