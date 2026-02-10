@props([
    'action',
    'hasFilters' => false,
    'searchPlaceholder' => __('Search...'),
    'searchValue' => '',
    'activeFiltersCount' => 0,
])

<div x-data="{ expanded: false }"
    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6 border border-gray-200 dark:border-gray-700">
    <form method="GET" action="{{ $action }}">
        {{-- Top Row: Search + Toggle + Action --}}
        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-grow">
                @if (isset($search))
                    {{ $search }}
                @else
                    <x-forms.input name="search" :value="$searchValue" :placeholder="$searchPlaceholder" class="w-full" />
                @endif
            </div>

            <div class="flex gap-2 shrink-0">
                {{-- Toggle Advanced Filters Button --}}
                <button type="button" @click="expanded = !expanded"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    {{ __('Advanced Filters') }}

                    {{-- Badge --}}
                    @if ($activeFiltersCount > 0)
                        <span
                            class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-blue-600 rounded-full">
                            {{ $activeFiltersCount }}
                        </span>
                    @endif

                    <x-fas-chevron-down class="ml-2 h-4 w-4 transition-transform duration-200" ::class="{ 'rotate-180': expanded }" />
                </button>

                {{-- Submit Button --}}
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-search class="h-4 w-4 mr-2" />
                    {{ __('Filter') }}
                </button>
            </div>
        </div>

        {{-- Collapsible Area --}}
        <div x-show="expanded" x-transition class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"
            style="display: none;">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Additional Filters Slot --}}
                {{ $slot }}
            </div>

            {{-- Clear Filters Button --}}
            @if ($hasFilters)
                <div class="mt-4 flex justify-end">
                    <a href="{{ $action }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <x-fas-times class="h-4 w-4 mr-2" />
                        {{ __('Clear Filters') }}
                    </a>
                </div>
            @endif
        </div>
    </form>
</div>
