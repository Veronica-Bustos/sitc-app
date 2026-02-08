@props([
    'name' => 'search',
    'placeholder' => __('Search...'),
    'debounce' => 300,
])

<div class="relative" x-data="{ query: '' }" x-init="$watch('query', value => {
    clearTimeout($refs.timeout);
    $refs.timeout = setTimeout(() => {
        $dispatch('search', { query: value });
    }, {{ $debounce }});
})">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <x-fas-search class="h-4 w-4 text-gray-400" />
    </div>
    <input type="text" name="{{ $name }}" x-model="query" placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' =>
                'w-full pl-10 pr-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
        ]) }}>
    <button type="button" x-show="query.length > 0" @click="query = ''; $dispatch('search', { query: '' })"
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
        <x-fas-times class="h-4 w-4" />
    </button>
</div>
