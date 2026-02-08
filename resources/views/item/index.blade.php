<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        itemToDelete: null,
        itemNameToDelete: '',
        confirmDelete(item) {
            this.itemToDelete = item.id;
            this.itemNameToDelete = item.name;
            this.deleteModal = true;
        },
        copyCode(code) {
            navigator.clipboard.writeText(code);
            this.$dispatch('notify', { message: '{{ __('Code copied to clipboard') }}' });
        }
    }" @notify.window="alert($event.detail.message)">

        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Items') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage inventory items') }}</p>
            </div>
            @can('create', App\Models\Item::class)
                <a href="{{ route('items.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('New Item') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6 border border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('items.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <x-forms.input name="search" :value="request('search')"
                        placeholder="{{ __('Search by code or name...') }}" />
                </div>
                <x-select name="category" :options="$categories->pluck('name', 'id')->toArray()" :value="request('category')"
                    placeholder="{{ __('All Categories') }}" />
                <x-select name="location" :options="$locations->pluck('name', 'id')->toArray()" :value="request('location')" placeholder="{{ __('All Locations') }}" />
                <div class="flex gap-2">
                    <x-select name="status" :options="$statuses" :value="request('status')" placeholder="{{ __('All Statuses') }}"
                        class="flex-1" />
                    @if (request()->hasAny(['search', 'category', 'location', 'status']))
                        <a href="{{ route('items.index') }}"
                            class="inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                            title="{{ __('Clear filters') }}">
                            <x-fas-times class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Code') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Name') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Category') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Location') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Condition') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <code
                                            class="text-sm font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $item->code }}</code>
                                        <button @click="copyCode('{{ $item->code }}')"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                            title="{{ __('Copy code') }}">
                                            <x-fas-copy class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('items.show', $item) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $item->category?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $item->currentLocation?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$item->status" size="sm" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 capitalize">
                                    {{ __('condition.' . $item->condition) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('items.show', $item) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="{{ __('View') }}">
                                            <x-fas-eye class="h-4 w-4" />
                                        </a>
                                        @can('update', $item)
                                            <a href="{{ route('items.edit', $item) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                title="{{ __('Edit') }}">
                                                <x-fas-edit class="h-4 w-4" />
                                            </a>
                                        @endcan
                                        @can('delete', $item)
                                            <button
                                                @click="confirmDelete({{ json_encode(['id' => $item->id, 'name' => $item->name]) }})"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                                title="{{ __('Delete') }}">
                                                <x-fas-trash class="h-4 w-4" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No items found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($items->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Item') }}" :message="__('Are you sure you want to delete this item? This action cannot be undone.')"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST" :action="'{{ route('items.index') }}/' + itemToDelete"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
