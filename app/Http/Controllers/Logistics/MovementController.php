<?php

namespace App\Http\Controllers\Logistics;

use App\Enums\MovementTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Logistics\MovementStoreRequest;
use App\Http\Requests\Logistics\MovementUpdateRequest;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovementController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(InventoryMovement::class, 'inventory_movement');
    }

    public function index(Request $request, \App\Filters\MovementFilter $filters): View
    {
        $query = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation', 'user']);

        $inventoryMovements = $query->filter($filters)
            ->paginate(20)
            ->withQueryString();

        return view('inventoryMovement.index', [
            'inventoryMovements' => $inventoryMovements,
            'items' => Item::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'movementTypes' => MovementTypeEnum::options(),
        ]);
    }

    public function create(Request $request): View
    {
        $preselectedItem = $request->query('item')
            ? Item::find($request->query('item'))
            : null;

        $preselectedFromLocation = $request->query('from_location')
            ? Location::find($request->query('from_location'))
            : null;

        $preselectedToLocation = $request->query('to_location')
            ? Location::find($request->query('to_location'))
            : null;

        return view('inventoryMovement.create', [
            'items' => Item::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'movementTypes' => MovementTypeEnum::options(),
            'preselectedItem' => $preselectedItem,
            'preselectedFromLocation' => $preselectedFromLocation,
            'preselectedToLocation' => $preselectedToLocation,
        ]);
    }

    public function store(MovementStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $inventoryMovement = InventoryMovement::create($data);

        // Update item's current location if applicable
        $item = Item::find($data['item_id']);
        if ($item && ! empty($data['to_location_id'])) {
            $item->update(['current_location_id' => $data['to_location_id']]);
        }

        $request->session()->flash('inventoryMovement.id', $inventoryMovement->id);

        return redirect()->route('inventory-movements.index')
            ->with('success', __('Movement registered successfully'));
    }

    public function show(Request $request, InventoryMovement $inventoryMovement): View
    {
        $inventoryMovement->load(['item', 'fromLocation', 'toLocation', 'user', 'attachments']);

        return view('inventoryMovement.show', [
            'inventoryMovement' => $inventoryMovement,
        ]);
    }

    public function edit(Request $request, InventoryMovement $inventoryMovement): View
    {
        $inventoryMovement->load(['item', 'fromLocation', 'toLocation']);

        return view('inventoryMovement.edit', [
            'inventoryMovement' => $inventoryMovement,
            'items' => Item::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'movementTypes' => MovementTypeEnum::options(),
        ]);
    }

    public function update(MovementUpdateRequest $request, InventoryMovement $inventoryMovement): RedirectResponse
    {
        $inventoryMovement->update($request->validated());

        $request->session()->flash('inventoryMovement.id', $inventoryMovement->id);

        return redirect()->route('inventory-movements.index')
            ->with('success', __('Movement updated successfully'));
    }

    public function destroy(Request $request, InventoryMovement $inventoryMovement): RedirectResponse
    {
        $inventoryMovement->delete();

        return redirect()->route('inventory-movements.index')
            ->with('success', __('Movement deleted successfully'));
    }
}
