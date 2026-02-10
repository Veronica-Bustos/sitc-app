@props(['name', 'label' => null])

@php
    $currentSort = request('sort');
    $isAsc = $currentSort === $name;
    $isDesc = $currentSort === '-' . $name;

    $nextSort = match (true) {
        $isAsc => '-' . $name,
        $isDesc => null,
        default => $name,
    };

    $url = request()->fullUrlWithQuery(['sort' => $nextSort]);
@endphp

<th scope="col"
    {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer group hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors']) }}>
    <a href="{{ $url }}" class="flex items-center gap-2 w-full h-full">
        {{ $label ?? $slot }}

        <span class="text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200">
            @if ($isAsc)
                <x-fas-sort-up class="w-3 h-3" />
            @elseif($isDesc)
                <x-fas-sort-down class="w-3 h-3" />
            @else
                <x-fas-sort class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity" />
            @endif
        </span>
    </a>
</th>
