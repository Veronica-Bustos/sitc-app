<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ItemConditionEnum;
use App\Enums\ItemStatusEnum;
use App\Enums\MovementTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Exports\MovementHistoryExport;
use App\Http\Exports\OutOfServiceExport;
use App\Http\Exports\StockByLocationExport;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /**
     * Landing page de reportes
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Reporte de Stock por Ubicación
     */
    public function stock(Request $request): View
    {
        $query = Item::query()
            ->with(['category', 'currentLocation']);

        // Aplicar filtros
        if ($request->filled('location_id')) {
            $query->where('current_location_id', $request->location_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%');
            });
        }

        $query->whereNull('deleted_at');

        $items = $query->get();

        // Calcular totales
        $totalItems = $items->count();
        $totalPurchaseValue = $items->sum('purchase_price');
        $totalCurrentValue = $items->sum('current_value');

        // Datos para filtros - usando Enums con traducciones
        $locations = Location::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $statuses = ItemStatusEnum::options();
        $conditions = ItemConditionEnum::options();

        // Obtener nombre de ubicación seleccionada para el export
        $selectedLocationName = null;
        if ($request->filled('location_id')) {
            $selectedLocation = $locations->firstWhere('id', $request->location_id);
            $selectedLocationName = $selectedLocation?->name;
        }

        return view('reports.stock', compact(
            'items',
            'totalItems',
            'totalPurchaseValue',
            'totalCurrentValue',
            'locations',
            'categories',
            'statuses',
            'conditions',
            'selectedLocationName'
        ));
    }

    /**
     * Exportar reporte de Stock a Excel
     */
    public function stockExport(Request $request): BinaryFileResponse
    {
        $query = Item::query()
            ->with(['category', 'currentLocation']);

        // Aplicar mismos filtros que en la vista
        if ($request->filled('location_id')) {
            $query->where('current_location_id', $request->location_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%');
            });
        }

        $query->whereNull('deleted_at');

        $items = $query->get();

        $selectedLocationName = null;
        if ($request->filled('location_id')) {
            $location = Location::find($request->location_id);
            $selectedLocationName = $location?->name;
        }

        $filename = 'stock_por_ubicacion_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(
            new StockByLocationExport($items, $selectedLocationName),
            $filename
        );
    }

    /**
     * Reporte de Historial de Movimientos
     */
    public function movements(Request $request): View
    {
        $query = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation', 'user']);

        // Aplicar filtros
        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('from_location_id')) {
            $query->where('from_location_id', $request->from_location_id);
        }

        if ($request->filled('to_location_id')) {
            $query->where('to_location_id', $request->to_location_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $movements = $query->orderByDesc('performed_at')->get();

        // Contadores por tipo
        $movementCounts = $movements->groupBy('movement_type')
            ->map(fn ($group) => $group->count());

        // Datos para filtros - usando Enums con traducciones
        $movementTypes = MovementTypeEnum::options();
        $items = Item::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('reports.movements', compact(
            'movements',
            'movementCounts',
            'movementTypes',
            'items',
            'locations',
            'users'
        ));
    }

    /**
     * Exportar reporte de Movimientos a Excel
     */
    public function movementsExport(Request $request): BinaryFileResponse
    {
        $query = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation', 'user']);

        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $movements = $query->orderByDesc('performed_at')->get();

        $filters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'movement_type' => $request->movement_type,
            'item_id' => $request->item_id,
        ];

        $filename = 'historial_movimientos_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(
            new MovementHistoryExport($movements, $filters),
            $filename
        );
    }

    /**
     * Reporte de Ítems Fuera de Servicio
     */
    public function outOfService(Request $request): View
    {
        $query = Item::query()
            ->with(['category', 'currentLocation', 'maintenanceRecords']);

        // Filtrar solo estados fuera de servicio
        $outOfServiceStatuses = ItemStatusEnum::outOfServiceOptions();

        if ($request->filled('status')) {
            $statuses = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statuses);
        } else {
            $query->whereIn('status', array_keys($outOfServiceStatuses));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        $query->whereNull('deleted_at');

        $items = $query->get();

        // Agregar días fuera de servicio a cada ítem
        $items->each(function ($item) {
            $item->days_out_of_service = $this->calculateDaysOutOfService($item);
        });

        // Contadores por estado
        $statusCounts = $items->groupBy('status')
            ->map(fn ($group) => $group->count());

        // Totales
        $totalItems = $items->count();
        $totalPurchaseValue = $items->sum('purchase_price');
        $totalCurrentValue = $items->sum('current_value');

        // Datos para filtros - usando Enums con traducciones
        $categories = Category::orderBy('name')->get();
        $outOfServiceStatuses = ItemStatusEnum::outOfServiceOptions();

        return view('reports.out-of-service', compact(
            'items',
            'statusCounts',
            'totalItems',
            'totalPurchaseValue',
            'totalCurrentValue',
            'categories',
            'outOfServiceStatuses'
        ));
    }

    /**
     * Exportar reporte de Fuera de Servicio a Excel
     */
    public function outOfServiceExport(Request $request): BinaryFileResponse
    {
        $query = Item::query()
            ->with(['category', 'currentLocation', 'maintenanceRecords']);

        $outOfServiceStatuses = ItemStatusEnum::outOfServiceOptions();

        if ($request->filled('status')) {
            $statuses = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statuses);
        } else {
            $query->whereIn('status', array_keys($outOfServiceStatuses));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        $query->whereNull('deleted_at');

        $items = $query->get();

        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
        ];

        $filename = 'items_fuera_servicio_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(
            new OutOfServiceExport($items, $filters),
            $filename
        );
    }

    /**
     * Calcular días fuera de servicio
     */
    private function calculateDaysOutOfService(Item $item): int
    {
        $lastMaintenance = $item->maintenanceRecords()
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest('request_date')
            ->first();

        if ($lastMaintenance) {
            return now()->diffInDays($lastMaintenance->request_date);
        }

        return now()->diffInDays($item->updated_at);
    }
}
