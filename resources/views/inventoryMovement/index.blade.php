<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        movementToDelete: null,
        movementIdToDelete: '',
        confirmDelete(movement) {
            this.movementToDelete = movement;
            this.movementIdToDelete = movement.id;
            this.deleteModal = true;
        }
    }">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Movements') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage inventory movements') }}</p>
            </div>
            @can('create', App\Models\InventoryMovement::class)
                <a href="{{ route('inventory-movements.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('New Movement') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <x-filters action="{{ route('inventory-movements.index') }}" :has-filters="request()->hasAny([
            'search',
            'movement_type',
            'item',
            'from_location',
            'to_location',
            'date_from',
            'date_to',
        ])" :active-filters-count="count(array_filter(request()->only(['movement_type', 'item', 'from_location', 'to_location', 'date_from', 'date_to']), fn($v) => $v !== null && $v !== ''))"
            search-placeholder="{{ __('Search by reason or notes...') }}" search-value="{{ request('search') }}">
            <x-select name="movement_type" :options="$movementTypes" :value="request('movement_type')" placeholder="{{ __('All Types') }}" />
            <x-select name="item" :options="$items->pluck('name', 'id')->toArray()" :value="request('item')" placeholder="{{ __('All Items') }}" />
            <x-select name="from_location" :options="$locations->pluck('name', 'id')->toArray()" :value="request('from_location')"
                placeholder="{{ __('From Location') }}" />
            <x-select name="to_location" :options="$locations->pluck('name', 'id')->toArray()" :value="request('to_location')" placeholder="{{ __('To Location') }}" />
            <div class="grid grid-cols-2 gap-2">
                <x-forms.input type="date" name="date_from" :value="request('date_from')" label="{{ __('From') }}" />
                <x-forms.input type="date" name="date_to" :value="request('date_to')" label="{{ __('To') }}" />
            </div>
        </x-filters>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <x-th-sortable name="performed_at">{{ __('Date') }}</x-th-sortable>
                            <x-th-sortable name="item">{{ __('Item') }}</x-th-sortable>
                            <x-th-sortable name="movement_type">{{ __('Type') }}</x-th-sortable>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('From') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('To') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('User') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($inventoryMovements as $movement)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $movement->performed_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('items.show', $movement->item) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $movement->item?->name ?? '-' }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $movement->item?->code ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeEnum = \App\Enums\MovementTypeEnum::tryFrom($movement->movement_type);
                                    @endphp
                                    @if ($typeEnum)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeEnum->color() }}">
                                            <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $typeEnum->icon())" class="h-3 w-3 mr-1" />
                                            {{ $typeEnum->label() }}
                                        </span>
                                    @else
                                        {{ $movement->movement_type }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $movement->fromLocation?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $movement->toLocation?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('inventory-movements.show', $movement) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="{{ __('View') }}">
                                            <x-fas-eye class="h-4 w-4" />
                                        </a>
                                        @can('update', $movement)
                                            <a href="{{ route('inventory-movements.edit', $movement) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                title="{{ __('Edit') }}">
                                                <x-fas-edit class="h-4 w-4" />
                                            </a>
                                        @endcan
                                        @can('delete', $movement)
                                            <button @click="confirmDelete({{ json_encode(['id' => $movement->id]) }})"
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
                                    {{ __('No movements found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($inventoryMovements->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $inventoryMovements->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Movement') }}" :message="__('Are you sure you want to delete this movement? This action cannot be undone.')"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST"
                :action="'{{ route('inventory-movements.index') }}/' + movementIdToDelete" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
