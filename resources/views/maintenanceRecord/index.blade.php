<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        maintenanceToDelete: null,
        maintenanceIdToDelete: '',
        confirmDelete(maintenance) {
            this.maintenanceToDelete = maintenance;
            this.maintenanceIdToDelete = maintenance.id;
            this.deleteModal = true;
        }
    }">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Maintenance Records') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage maintenance requests') }}</p>
            </div>
            @can('create', App\Models\MaintenanceRecord::class)
                <a href="{{ route('maintenance-records.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('New Maintenance') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <x-filters action="{{ route('maintenance-records.index') }}" :has-filters="request()->hasAny([
            'search',
            'status',
            'type',
            'priority',
            'item',
            'technician',
            'date_from',
            'date_to',
        ])" :active-filters-count="count(array_filter(request()->only(['status', 'type', 'priority', 'item', 'technician', 'date_from', 'date_to']), fn($v) => $v !== null && $v !== ''))"
            search-placeholder="{{ __('Search by description...') }}" search-value="{{ request('search') }}">
            <x-select name="status" :options="$statuses" :value="request('status')" placeholder="{{ __('All Statuses') }}" />
            <x-select name="type" :options="$types" :value="request('type')" placeholder="{{ __('All Types') }}" />
            <x-select name="priority" :options="$priorities" :value="request('priority')" placeholder="{{ __('All Priorities') }}" />
            <x-select name="item" :options="$items->pluck('name', 'id')->toArray()" :value="request('item')" placeholder="{{ __('All Items') }}" />
            <x-select name="technician" :options="$technicians->pluck('name', 'id')->toArray()" :value="request('technician')"
                placeholder="{{ __('All Technicians') }}" />
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
                            <x-th-sortable name="request_date">{{ __('Request Date') }}</x-th-sortable>
                            <x-th-sortable name="item">{{ __('Item') }}</x-th-sortable>
                            <x-th-sortable name="type">{{ __('Type') }}</x-th-sortable>
                            <x-th-sortable name="priority">{{ __('Priority') }}</x-th-sortable>
                            <x-th-sortable name="status">{{ __('Status') }}</x-th-sortable>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Technician') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($maintenanceRecords as $maintenance)
                            @php
                                $typeEnum = \App\Enums\MaintenanceTypeEnum::tryFrom($maintenance->type);
                                $statusEnum = \App\Enums\MaintenanceStatusEnum::tryFrom($maintenance->status);
                                $priorityEnum = \App\Enums\MaintenancePriorityEnum::tryFrom($maintenance->priority);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $maintenance->request_date?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('items.show', $maintenance->item) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $maintenance->item?->name ?? '-' }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $maintenance->item?->code ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($typeEnum)
                                        <span class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $typeEnum->icon())"
                                                class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ $typeEnum->label() }}
                                        </span>
                                    @else
                                        {{ $maintenance->type }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($priorityEnum)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityEnum->color() }}">
                                            <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $priorityEnum->icon())" class="h-3 w-3 mr-1" />
                                            {{ $priorityEnum->label() }}
                                        </span>
                                    @else
                                        {{ $maintenance->priority }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($statusEnum)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusEnum->color() }}">
                                            {{ $statusEnum->label() }}
                                        </span>
                                    @else
                                        {{ $maintenance->status }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $maintenance->technician?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('maintenance-records.show', $maintenance) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="{{ __('View') }}">
                                            <x-fas-eye class="h-4 w-4" />
                                        </a>
                                        @can('update', $maintenance)
                                            <a href="{{ route('maintenance-records.edit', $maintenance) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                title="{{ __('Edit') }}">
                                                <x-fas-edit class="h-4 w-4" />
                                            </a>
                                        @endcan
                                        @can('delete', $maintenance)
                                            <button @click="confirmDelete({{ json_encode(['id' => $maintenance->id]) }})"
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
                                    {{ __('No maintenance records found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($maintenanceRecords->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $maintenanceRecords->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Maintenance Record') }}" :message="__('Are you sure you want to delete this maintenance record? This action cannot be undone.')"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST"
                :action="'{{ route('maintenance-records.index') }}/' + maintenanceIdToDelete" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
