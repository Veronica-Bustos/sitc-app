@props([
    'headers' => [],
    'rows' => [],
    'emptyMessage' => __('No data'),
    'sortable' => false,
    'sortField' => null,
    'sortDirection' => 'asc',
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                @foreach ($headers as $key => $header)
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider {{ $header['class'] ?? '' }}">
                        @if ($sortable && ($header['sortable'] ?? false))
                            <button type="button" @click="$dispatch('sort', { field: '{{ $key }}' })"
                                class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-100">
                                {{ $header['label'] }}
                                @if ($sortField === $key)
                                    <x-fas-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3" />
                                @else
                                    <x-fas-sort class="w-3 h-3 opacity-50" />
                                @endif
                            </button>
                        @else
                            {{ is_array($header) ? $header['label'] : $header }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($rows as $row)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    {{ $slot }}
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
