<x-layouts.app>
    <div>
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('maintenance-records.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Maintenance Records') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('New Maintenance Record') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create a new maintenance request') }}</p>
        </div>

        <form method="POST" action="{{ route('maintenance-records.store') }}" class="max-w-4xl">
            @csrf

            <!-- Request Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Request Information') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Basic maintenance details') }}</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Selection -->
                        <div class="md:col-span-2">
                            <x-select label="{{ __('Item') }} *" name="item_id" :options="$items->pluck('name', 'id')->toArray()"
                                value="{{ $preselectedItem?->id ?? old('item_id') }}" required />
                            @if ($preselectedItem)
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Code') }}: {{ $preselectedItem->code }} | {{ __('Current location') }}:
                                    {{ $preselectedItem->currentLocation?->name ?? '-' }}
                                </p>
                            @endif
                        </div>

                        <!-- Maintenance Type -->
                        <div>
                            <x-select label="{{ __('Type') }} *" name="type" :options="$types" :value="old('type', 'CORRECTIVE')"
                                required />
                        </div>

                        <!-- Priority -->
                        <div>
                            <x-select label="{{ __('Priority') }} *" name="priority" :options="$priorities"
                                :value="old('priority', 'MEDIUM')" required />
                        </div>

                        <!-- Request Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Request Date') }} *" name="request_date"
                                :value="old('request_date', now()->format('Y-m-d'))" required />
                        </div>

                        <!-- Technician -->
                        <div>
                            <x-select label="{{ __('Assigned Technician') }}" name="technician_id" :options="$technicians->pluck('name', 'id')->toArray()"
                                :value="old('technician_id')" placeholder="{{ __('Select technician') }}" />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Description') }} *" name="description" :value="old('description')"
                                rows="4" placeholder="{{ __('Describe the problem or maintenance needed...') }}"
                                required />
                        </div>

                        <!-- Cost -->
                        <div>
                            <x-forms.input type="number" step="0.01" label="{{ __('Estimated Cost') }}"
                                name="cost" :value="old('cost')" placeholder="0.00" />
                        </div>

                        <!-- Next Maintenance Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Next Maintenance Date') }}"
                                name="next_maintenance_date" :value="old('next_maintenance_date')" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <x-fas-save class="h-5 w-5 mr-2" />
                    {{ __('Create Maintenance Record') }}
                </button>
                <a href="{{ route('maintenance-records.index') }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
