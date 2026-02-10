<x-layouts.app>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('locations.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ __('Locations') }}
                </a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $location->name }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-lg flex items-center justify-center
                        @if ($location->type === 'WAREHOUSE') bg-blue-100 dark:bg-blue-900
                        @elseif ($location->type === 'SITE') bg-orange-100 dark:bg-orange-900
                        @else bg-gray-100 dark:bg-gray-700 @endif">
                        @if ($location->type === 'WAREHOUSE')
                            <x-fas-warehouse class="h-6 w-6 text-blue-600 dark:text-blue-300" />
                        @elseif ($location->type === 'SITE')
                            <x-fas-hard-hat class="h-6 w-6 text-orange-600 dark:text-orange-300" />
                        @else
                            <x-fas-building class="h-6 w-6 text-gray-600 dark:text-gray-300" />
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $location->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <code class="text-sm font-mono text-gray-500">{{ $location->code }}</code>
                            <span class="text-sm text-gray-500">•</span>
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ __($location->type) }}</span>
                            <x-status-badge :status="$location->status" size="sm" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('update', $location)
                        <a href="{{ route('locations.edit', $location) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            <x-fas-edit class="h-4 w-4 mr-2" />
                            {{ __('Edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('locations.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Items') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $location->items_count ?? $location->items()->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <x-fas-box class="h-6 w-6 text-blue-600 dark:text-blue-300" />
                    </div>
                </div>
            </div>

            <!-- Available Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Available') }}</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $location->items()->where('status', 'available')->count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <x-fas-check-circle class="h-6 w-6 text-green-600 dark:text-green-300" />
                    </div>
                </div>
            </div>

            <!-- In Use -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('In Use') }}</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $location->items()->where('status', 'in_use')->count() }}
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        <x-fas-hand-holding class="h-6 w-6 text-orange-600 dark:text-orange-300" />
                    </div>
                </div>
            </div>

            <!-- In Maintenance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('In Repair') }}</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ $location->items()->where('status', 'in_repair')->count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <x-fas-wrench class="h-6 w-6 text-yellow-600 dark:text-yellow-300" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Location Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        {{ __('Location Information') }}</h2>

                    <div class="space-y-3">
                        @if ($location->address)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</p>
                                <p class="text-gray-800 dark:text-gray-200 mt-1">{{ $location->address }}</p>
                            </div>
                        @endif

                        @if ($location->coordinates)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Coordinates') }}
                                </p>
                                <code
                                    class="text-sm font-mono text-gray-700 dark:text-gray-300 mt-1 block">{{ $location->coordinates }}</code>
                            </div>
                        @endif

                        @if ($location->responsibleUser)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Responsible') }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <x-fas-user class="h-3 w-3 text-blue-600 dark:text-blue-300" />
                                    </div>
                                    <span
                                        class="text-gray-800 dark:text-gray-200">{{ $location->responsibleUser->name }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($location->parent)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Parent Location') }}</p>
                                <a href="{{ route('locations.show', $location->parent) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline mt-1 block">
                                    {{ $location->parent->name }}
                                </a>
                            </div>
                        @endif

                        @if ($location->start_date || $location->end_date)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                @if ($location->start_date)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Start Date') }}: {{ $location->start_date->format('d/m/Y') }}
                                    </p>
                                @endif
                                @if ($location->end_date)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ __('End Date') }}: {{ $location->end_date->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if ($location->notes)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Notes') }}</p>
                                <p class="text-gray-800 dark:text-gray-200 mt-1 text-sm">{{ $location->notes }}</p>
                            </div>
                        @endif

                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Created') }}: {{ $location->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sub-locations -->
                @php
                    $children = $location->children ?? $location->children()->withCount('items')->get();
                @endphp
                @if ($children->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                            {{ __('Sub-locations') }}</h2>

                        <div class="space-y-2">
                            @foreach ($children as $child)
                                <a href="{{ route('locations.show', $child) }}"
                                    class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center gap-2">
                                        @if ($child->type === 'WAREHOUSE')
                                            <x-fas-warehouse class="h-4 w-4 text-blue-500" />
                                        @elseif ($child->type === 'SITE')
                                            <x-fas-hard-hat class="h-4 w-4 text-orange-500" />
                                        @else
                                            <x-fas-building class="h-4 w-4 text-gray-500" />
                                        @endif
                                        <span class="text-gray-800 dark:text-gray-200">{{ $child->name }}</span>
                                    </div>
                                    <span
                                        class="text-sm text-gray-500">{{ $child->items_count ?? $child->items()->count() }}
                                        {{ __('items') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Items List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ __('Items at this Location') }}</h2>
                            @can('create', App\Models\Item::class)
                                <a href="{{ route('items.create', ['location_id' => $location->id]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                                    <x-fas-plus class="h-4 w-4 mr-2" />
                                    {{ __('Add Item') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    @php
                        $items = $location->items()->with('category')->latest()->take(10)->get();
                    @endphp

                    @if ($items->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            {{ __('Code') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            {{ __('Name') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            {{ __('Category') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            {{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700 dark:text-gray-300">
                                                <a href="{{ route('items.show', $item) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $item->code }}
                                                </a>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                <a href="{{ route('items.show', $item) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $item->category?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <x-status-badge :status="$item->status" size="sm" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($location->items()->count() > 10)
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                                <a href="{{ route('items.index', ['location' => $location->id]) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                    {{ __('View all :count items', ['count' => $location->items()->count()]) }} →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <x-fas-warehouse class="h-12 w-12 mx-auto mb-3 text-gray-300" />
                            <p>{{ __('No items at this location yet') }}</p>
                            @can('create', App\Models\Item::class)
                                <a href="{{ route('items.create', ['location_id' => $location->id]) }}"
                                    class="inline-flex items-center mt-3 text-blue-600 dark:text-blue-400 hover:underline">
                                    <x-fas-plus class="h-4 w-4 mr-1" />
                                    {{ __('Add first item') }}
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
