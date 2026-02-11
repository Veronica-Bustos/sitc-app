<x-layouts.app>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Out of Service Items') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Items in repair, damaged, lost, or retired status') }}</p>
        </div>
        <x-button href="{{ route('reports.index') }}" variant="outline">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Back to Reports') }}
        </x-button>
    </div>

    {{-- Filtros usando componente x-filters --}}
    <x-filters action="{{ route('reports.out-of-service') }}" :has-filters="request()->hasAny(['status', 'category_id', 'date_from'])" :active-filters-count="count(array_filter(request()->only(['status', 'category_id', 'date_from']), fn($v) => $v !== null && $v !== ''))">

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
            <select name="status[]" multiple
                class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent h-24">
                @foreach ($outOfServiceStatuses as $key => $label)
                    <option value="{{ $key }}"
                        {{ in_array($key, (array) request('status', [])) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">{{ __('Hold Ctrl/Cmd to select multiple') }}</p>
        </div>

        <x-select name="category_id" :options="$categories->pluck('name', 'id')->toArray()" :value="request('category_id')" placeholder="{{ __('All Categories') }}" />

        <div>
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Out of Service Since') }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </x-filters>

    {{-- Export Button --}}
    <div class="mb-4 flex justify-end">
        @if ($items->count() > 0)
            <a href="{{ route('reports.out-of-service.export', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <x-icon name="fas-file-excel" class="w-4 h-4 mr-2" />
                {{ __('Export Excel') }}
            </a>
        @endif
    </div>

    {{-- Resumen por estado --}}
    @if ($statusCounts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @foreach ($statusCounts as $status => $count)
                @php
                    $enum = \App\Enums\ItemStatusEnum::tryFrom($status);
                    $colorClass = $enum?->color() ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                @endphp
                <div class="{{ $colorClass }} rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold">{{ $count }}</p>
                    <p class="text-sm">{{ $outOfServiceStatuses[$status] ?? $status }}</p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Resumen general --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                    <x-icon name="fas-exclamation-triangle" class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Out of Service') }}</p>
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
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Items Out of Service') }}</h2>
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
                                {{ __('Status') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Category') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Last Location') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Days Out') }}</th>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$item->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->category?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->currentLocation?->name ?? 'Sin ubicación' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->days_out_of_service > 30 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $item->days_out_of_service }} {{ __('days') }}
                                    </span>
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
                <x-icon name="fas-check-circle" class="w-12 h-12 mx-auto mb-4 text-green-500" />
                <p>{{ __('No items out of service found') }}</p>
                <p class="text-sm mt-1">{{ __('All inventory items are currently available or in use') }}</p>
            </div>
        @endif
    </div>
</x-layouts.app>
