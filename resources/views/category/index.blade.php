<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        itemToDelete: null,
        itemNameToDelete: '',
        confirmDelete(category) {
            this.itemToDelete = category.id;
            this.itemNameToDelete = category.name;
            this.deleteModal = true;
        }
    }">

        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Categories') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage item categories') }}</p>
            </div>
            @can('create', App\Models\Category::class)
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('New Category') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <x-filters action="{{ route('categories.index') }}" :has-filters="request()->hasAny(['search', 'parent_id', 'is_active'])" :active-filters-count="count(array_filter(request()->only(['parent_id', 'is_active']), fn($v) => $v !== null && $v !== ''))"
            search-placeholder="{{ __('Search by name or slug...') }}" search-value="{{ request('search') }}">
            <x-select name="parent_id" :options="$parents->pluck('name', 'id')->toArray()" :value="request('parent_id')" placeholder="{{ __('All Parents') }}" />
            <x-select name="is_active" :options="['1' => __('Active'), '0' => __('Inactive')]" :value="request('is_active')" placeholder="{{ __('All Statuses') }}" />
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
                                {{ __('Name') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Slug') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Items') }}
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
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('categories.show', $category) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $category->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $category->slug }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $category->items_count ?? $category->items()->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$category->is_active" size="sm" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('categories.show', $category) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="{{ __('View') }}">
                                            <x-fas-eye class="h-4 w-4" />
                                        </a>
                                        @can('update', $category)
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                                title="{{ __('Edit') }}">
                                                <x-fas-edit class="h-4 w-4" />
                                            </a>
                                        @endcan
                                        @can('delete', $category)
                                            <button
                                                @click="confirmDelete({{ json_encode(['id' => $category->id, 'name' => $category->name]) }})"
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No categories found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Category') }}" :message="__('Are you sure you want to delete this category? This action cannot be undone.')"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST" :action="'{{ route('categories.index') }}/' + itemToDelete"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
