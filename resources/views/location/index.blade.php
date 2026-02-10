<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        itemToDelete: null,
        itemNameToDelete: '',
        confirmDelete(location) {
            this.itemToDelete = location.id;
            this.itemNameToDelete = location.name;
            this.deleteModal = true;
        }
    }">

        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Locations') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage storage locations') }}</p>
            </div>
            @can('create', App\Models\Location::class)
                <a href="{{ route('locations.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('New Location') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <x-filters action="{{ route('locations.index') }}" :has-filters="request()->hasAny(['search', 'type', 'responsible_user_id', 'status'])" :active-filters-count="count(array_filter(request()->only(['type', 'responsible_user_id', 'status']), fn($v) => $v !== null && $v !== ''))"
            search-placeholder="{{ __('Search by name, code or address...') }}" search-value="{{ request('search') }}">
            <x-select name="type" :options="$types->mapWithKeys(fn($t) => [$t => ucfirst($t)])->toArray()" :value="request('type')" placeholder="{{ __('All Types') }}" />
            <x-select name="responsible_user_id" :options="$responsibles->pluck('name', 'id')->toArray()" :value="request('responsible_user_id')"
                placeholder="{{ __('All Responsibles') }}" />
            <x-select name="status" :options="['ACTIVE' => __('Active'), 'INACTIVE' => __('Inactive'), 'CLOSED' => __('Closed')]" :value="request('status')" placeholder="{{ __('All Statuses') }}" />
        </x-filters>

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
                                {{ __('Type') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Items') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Responsible') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($locations as $location)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 font-mono">
                                    {{ $location->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('locations.show', $location) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $location->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 capitalize">
                                    {{ $location->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $location->items_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $location->responsibleUser?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$location->status" size="sm" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('locations.show', $location) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="{{ __('View') }}">
                                            <x-fas-eye class="h-4 w-4" />
                                        </a>
                                        @can('update', $location)
                                            <a href="{{ route('locations.edit', $location) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                title="{{ __('Edit') }}">
                                                <x-fas-edit class="h-4 w-4" />
                                            </a>
                                        @endcan
                                        @can('delete', $location)
                                            <button
                                                @click="confirmDelete({{ json_encode(['id' => $location->id, 'name' => $location->name]) }})"
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
                                    {{ __('No locations found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($locations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Location') }}" :message="__('Are you sure you want to delete this location? This action cannot be undone.')"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST" :action="'{{ route('locations.index') }}/' + itemToDelete"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
