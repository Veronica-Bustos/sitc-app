<x-layouts.app>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('categories.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ __('Categories') }}
                </a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $category->name }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    @if ($category->icon)
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                            style="background-color: {{ $category->color ?? '#3B82F6' }}20">
                            <x-dynamic-component :component="$category->icon" class="h-6 w-6"
                                style="color: {{ $category->color ?? '#3B82F6' }}" />
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                            style="background-color: {{ $category->color ?? '#3B82F6' }}20">
                            <x-fas-folder class="h-6 w-6" style="color: {{ $category->color ?? '#3B82F6' }}" />
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $category->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm text-gray-500 font-mono">{{ $category->slug }}</span>
                            <x-status-badge :status="$category->is_active ? 'active' : 'inactive'" size="sm" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('update', $category)
                        <a href="{{ route('categories.edit', $category) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            <x-fas-edit class="h-4 w-4 mr-2" />
                            {{ __('Edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Items') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $category->items_count ?? $category->items()->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <x-fas-box class="h-6 w-6 text-blue-600 dark:text-blue-300" />
                    </div>
                </div>
            </div>

            <!-- Active Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active Items') }}</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $category->items()->where('status', 'available')->count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <x-fas-check-circle class="h-6 w-6 text-green-600 dark:text-green-300" />
                    </div>
                </div>
            </div>

            <!-- Items In Use -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Items In Use') }}</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $category->items()->where('status', 'in_use')->count() }}
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        <x-fas-hand-holding class="h-6 w-6 text-orange-600 dark:text-orange-300" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Category Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        {{ __('Category Information') }}</h2>

                    <div class="space-y-3">
                        @if ($category->description)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}
                                </p>
                                <p class="text-gray-800 dark:text-gray-200 mt-1">{{ $category->description }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Color') }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="w-6 h-6 rounded border border-gray-300"
                                    style="background-color: {{ $category->color ?? '#3B82F6' }}">
                                </div>
                                <code
                                    class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $category->color ?? '#3B82F6' }}</code>
                            </div>
                        </div>

                        @if ($category->icon)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Icon') }}</p>
                                <code
                                    class="text-sm font-mono text-gray-700 dark:text-gray-300 mt-1 block">{{ $category->icon }}</code>
                            </div>
                        @endif

                        @if ($category->parent)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Parent Category') }}</p>
                                <a href="{{ route('categories.show', $category->parent) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline mt-1 block">
                                    {{ $category->parent->name }}
                                </a>
                            </div>
                        @endif

                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Created') }}: {{ $category->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ __('Updated') }}: {{ $category->updated_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Subcategories -->
                @if ($category->children && $category->children->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                            {{ __('Subcategories') }}</h2>

                        <div class="space-y-2">
                            @foreach ($category->children as $child)
                                <a href="{{ route('categories.show', $child) }}"
                                    class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center gap-2">
                                        @if ($child->icon)
                                            <x-dynamic-component :component="$child->icon" class="h-4 w-4"
                                                style="color: {{ $child->color ?? '#3B82F6' }}" />
                                        @else
                                            <x-fas-folder class="h-4 w-4 text-gray-500" />
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
                                {{ __('Items in this Category') }}</h2>
                            @can('create', App\Models\Item::class)
                                <a href="{{ route('items.create', ['category_id' => $category->id]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                                    <x-fas-plus class="h-4 w-4 mr-2" />
                                    {{ __('Add Item') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    @php
                        $items = $category->items()->with('currentLocation')->latest()->take(10)->get();
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
                                            {{ __('Location') }}</th>
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
                                                <a href="{{ route('items.show', $item) }}" class="hover:text-blue-600">
                                                    {{ $item->code }}
                                                </a>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                <a href="{{ route('items.show', $item) }}" class="hover:text-blue-600">
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $item->currentLocation?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <x-status-badge :status="$item->status" size="sm" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($category->items()->count() > 10)
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                                <a href="{{ route('items.index', ['category' => $category->id]) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                    {{ __('View all :count items', ['count' => $category->items()->count()]) }} →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <x-fas-box-open class="h-12 w-12 mx-auto mb-3 text-gray-300" />
                            <p>{{ __('No items in this category yet') }}</p>
                            @can('create', App\Models\Item::class)
                                <a href="{{ route('items.create', ['category_id' => $category->id]) }}"
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
