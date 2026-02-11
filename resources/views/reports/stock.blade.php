<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Stock by Location') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Inventory stock report with location distribution') }}</p>
        </div>
        <x-button href="{{ route('reports.index') }}" variant="outline">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Back to Reports') }}
        </x-button>
    </div>

    {{-- Filtros usando componente x-filters --}}
    <x-filters action="{{ route('reports.stock') }}" :has-filters="request()->hasAny(['search', 'location_id', 'category_id', 'status', 'condition'])" :active-filters-count="count(array_filter(request()->only(['location_id', 'category_id', 'status', 'condition']), fn($v) => $v !== null && $v !== ''))"
        search-placeholder="{{ __('Search by code or name...') }}" search-value="{{ request('search') }}">

        <x-select name="location_id" :options="$locations->pluck('name', 'id')->toArray()" :value="request('location_id')" placeholder="{{ __('All Locations') }}" />
        <x-select name="category_id" :options="$categories->pluck('name', 'id')->toArray()" :value="request('category_id')" placeholder="{{ __('All Categories') }}" />
        <x-select name="status" :options="$statuses" :value="request('status')" placeholder="{{ __('All Statuses') }}" />
        <x-select name="condition" :options="$conditions" :value="request('condition')" placeholder="{{ __('All Conditions') }}" />
    </x-filters>

    {{-- Export Button --}}
    <div class="mb-4 flex justify-end">
        @if ($items->count() > 0)
            <a href="{{ route('reports.stock.export', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <x-icon name="fas-file-excel" class="w-4 h-4 mr-2" />
                {{ __('Export Excel') }}
            </a>
        @endif
    </div>

    {{-- Resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <x-icon name="fas-boxes" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Items') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalItems }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <x-icon name="fas-dollar-sign" class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Purchase Value') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format($totalPurchaseValue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <x-icon name="fas-coins" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Current Value') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format($totalCurrentValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de resultados --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Results') }}</h2>
        </div>

        @if ($items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Code') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Name') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Category') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Location') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Condition') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Purchase Price') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Current Value') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900 dark:text-white">
                                    {{ $item->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('items.show', $item) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">{{ $item->name }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->category?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->currentLocation?->name ?? 'Sin ubicación' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$item->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($item->condition)
                                        {{ __('condition.' . $item->condition) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($item->purchase_price ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($item->current_value ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                <x-icon name="fas-inbox" class="w-12 h-12 mx-auto mb-4" />
                <p>{{ __('No items found with the selected filters') }}</p>
            </div>
        @endif
    </div>
</x-layouts.app>
