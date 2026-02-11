<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreRequest as ItemStoreRequest;
use App\Http\Requests\Inventory\UpdateRequest as ItemUpdateRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Item::class, 'item');
    }

    public function index(Request $request, \App\Filters\ItemFilter $filters): View
    {
        $query = Item::query()->with(['category', 'currentLocation']);

        $items = $query->filter($filters)->paginate(20)->withQueryString();

        return view('item.index', [
            'items' => $items,
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'statuses' => [
                'available' => __('status.available'),
                'in_use' => __('status.in_use'),
                'in_repair' => __('status.in_repair'),
                'damaged' => __('status.damaged'),
                'lost' => __('status.lost'),
                'retired' => __('status.retired'),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('item.create', [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'statuses' => [
                'available' => __('status.available'),
                'in_use' => __('status.in_use'),
                'in_repair' => __('status.in_repair'),
                'damaged' => __('status.damaged'),
                'lost' => __('status.lost'),
                'retired' => __('status.retired'),
            ],
            'conditions' => [
                'excellent' => __('condition.excellent'),
                'good' => __('condition.good'),
                'fair' => __('condition.fair'),
                'poor' => __('condition.poor'),
            ],
        ]);
    }

    public function store(ItemStoreRequest $request): RedirectResponse
    {
        $item = Item::create($request->validated());

        $request->session()->flash('item.id', $item->id);

        return redirect()->route('items.index');
    }

    public function show(Request $request, Item $item): View
    {
        $item->load(['category', 'currentLocation', 'attachments', 'inventoryMovements' => function ($query) {
            $query->latest()->limit(5)->with(['fromLocation', 'toLocation', 'user']);
        }]);

        return view('item.show', [
            'item' => $item,
        ]);
    }

    public function edit(Request $request, Item $item): View
    {
        return view('item.edit', [
            'item' => $item,
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'statuses' => [
                'available' => __('status.available'),
                'in_use' => __('status.in_use'),
                'in_repair' => __('status.in_repair'),
                'damaged' => __('status.damaged'),
                'lost' => __('status.lost'),
                'retired' => __('status.retired'),
            ],
            'conditions' => [
                'excellent' => __('condition.excellent'),
                'good' => __('condition.good'),
                'fair' => __('condition.fair'),
                'poor' => __('condition.poor'),
            ],
        ]);
    }

    public function history(Request $request, Item $item): View
    {
        $this->authorize('history', $item);

        $movements = $item->inventoryMovements()
            ->with(['fromLocation', 'toLocation', 'user'])
            ->latest()
            ->paginate(20);

        return view('item.history', [
            'item' => $item,
            'movements' => $movements,
        ]);
    }

    public function update(ItemUpdateRequest $request, Item $item): RedirectResponse
    {
        $item->update($request->validated());

        $request->session()->flash('item.id', $item->id);

        return redirect()->route('items.index');
    }

    public function destroy(Request $request, Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index');
    }
}
