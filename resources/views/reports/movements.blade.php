<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Movement History') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Track all inventory movements and transactions') }}
            </p>
        </div>
        <x-button href="{{ route('reports.index') }}" variant="outline">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Back to Reports') }}
        </x-button>
    </div>

    {{-- Filtros usando componente x-filters --}}
    <x-filters action="{{ route('reports.movements') }}" :has-filters="request()->hasAny([
        'date_from',
        'date_to',
        'movement_type',
        'item_id',
        'from_location_id',
        'to_location_id',
        'user_id',
    ])" :active-filters-count="count(array_filter(request()->only(['date_from', 'date_to', 'movement_type', 'item_id', 'from_location_id', 'to_location_id', 'user_id']), fn($v) => $v !== null && $v !== ''))">

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date From') }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date To') }}</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <x-select name="movement_type" :options="$movementTypes" :value="request('movement_type')" placeholder="{{ __('All Types') }}" />
        <x-select name="item_id" :options="$items->pluck('name', 'id')->toArray()" :value="request('item_id')" placeholder="{{ __('All Items') }}" />
        <x-select name="from_location_id" :options="$locations->pluck('name', 'id')->toArray()" :value="request('from_location_id')" placeholder="{{ __('All Origins') }}" />
        <x-select name="to_location_id" :options="$locations->pluck('name', 'id')->toArray()" :value="request('to_location_id')"
            placeholder="{{ __('All Destinations') }}" />
        <x-select name="user_id" :options="$users->pluck('name', 'id')->toArray()" :value="request('user_id')" placeholder="{{ __('All Users') }}" />
    </x-filters>

    {{-- Export Button --}}
    <div class="mb-4 flex justify-end">
        @if ($movements->count() > 0)
            <a href="{{ route('reports.movements.export', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <x-icon name="fas-file-excel" class="w-4 h-4 mr-2" />
                {{ __('Export Excel') }}
            </a>
        @endif
    </div>

    {{-- Resumen por tipo --}}
    @if ($movementCounts->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Summary by Type') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach ($movementCounts as $type => $count)
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $count }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $movementTypes[$type] ?? $type }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Tabla de resultados --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Movements') }}</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total') }}:
                {{ $movements->count() }}</span>
        </div>

        @if ($movements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Date') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Item') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Type') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('From') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('To') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('User') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Quantity') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Reference') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($movements as $movement)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $movement->performed_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($movement->item)
                                        <a href="{{ route('items.show', $movement->item) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                            {{ $movement->item->code }} - {{ $movement->item->name }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-movement-type-badge :type="$movement->movement_type" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movement->fromLocation?->name ?? 'Inventario' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movement->toLocation?->name ?? 'Externo' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movement->user?->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $movement->quantity ?? 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movement->reference_document ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                <x-icon name="fas-inbox" class="w-12 h-12 mx-auto mb-4" />
                <p>{{ __('No movements found with the selected filters') }}</p>
            </div>
        @endif
    </div>
</x-layouts.app>
