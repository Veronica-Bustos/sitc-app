<x-layouts.app>
    <div>
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('maintenance-records.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Maintenance Records') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Maintenance Record') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update maintenance record details') }}</p>
        </div>

        <form method="POST" action="{{ route('maintenance-records.update', $maintenanceRecord) }}" class="max-w-4xl">
            @csrf
            @method('PUT')

            <!-- Request Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Request Information') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Basic maintenance details') }}</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Selection (Read-only) -->
                        <div class="md:col-span-2">
                            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Item') }}
                            </label>
                            <div
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
                                @if ($maintenanceRecord->item)
                                    {{ $maintenanceRecord->item->name }} ({{ $maintenanceRecord->item->code }})
                                @else
                                    -
                                @endif
                            </div>
                            <input type="hidden" name="item_id" value="{{ $maintenanceRecord->item_id }}">
                        </div>

                        <!-- Maintenance Type -->
                        <div>
                            <x-select label="{{ __('Type') }} *" name="type" :options="$types" :value="old('type', $maintenanceRecord->type)"
                                required />
                        </div>

                        <!-- Priority -->
                        <div>
                            <x-select label="{{ __('Priority') }} *" name="priority" :options="$priorities"
                                :value="old('priority', $maintenanceRecord->priority)" required />
                        </div>

                        <!-- Request Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Request Date') }} *" name="request_date"
                                :value="old('request_date', $maintenanceRecord->request_date?->format('Y-m-d'))" required />
                        </div>

                        <!-- Technician -->
                        <div>
                            <x-select label="{{ __('Assigned Technician') }}" name="technician_id" :options="$technicians->pluck('name', 'id')->toArray()"
                                :value="old('technician_id', $maintenanceRecord->technician_id)" placeholder="{{ __('Select technician') }}" />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Description') }} *" name="description" :value="old('description', $maintenanceRecord->description)"
                                rows="4" placeholder="{{ __('Describe the problem or maintenance needed...') }}"
                                required />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Details -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Work Details') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Information about the work performed') }}</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <x-select label="{{ __('Status') }} *" name="status" :options="$statuses" :value="old('status', $maintenanceRecord->status)"
                                required />
                        </div>

                        <!-- Intervention Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Intervention Date') }}"
                                name="intervention_date" :value="old(
                                    'intervention_date',
                                    $maintenanceRecord->intervention_date?->format('Y-m-d'),
                                )" />
                        </div>

                        <!-- Completion Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Completion Date') }}" name="completion_date"
                                :value="old(
                                    'completion_date',
                                    $maintenanceRecord->completion_date?->format('Y-m-d'),
                                )" />
                        </div>

                        <!-- Cost -->
                        <div>
                            <x-forms.input type="number" step="0.01" label="{{ __('Cost') }}" name="cost"
                                :value="old('cost', $maintenanceRecord->cost)" placeholder="0.00" />
                        </div>

                        <!-- Diagnosis -->
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Diagnosis') }}" name="diagnosis" :value="old('diagnosis', $maintenanceRecord->diagnosis)"
                                rows="3" placeholder="{{ __('Technical diagnosis of the problem...') }}" />
                        </div>

                        <!-- Actions Taken -->
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Actions Taken') }}" name="actions_taken" :value="old('actions_taken', $maintenanceRecord->actions_taken)"
                                rows="3" placeholder="{{ __('Describe the work performed...') }}" />
                        </div>

                        <!-- Parts Replaced -->
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Parts Replaced') }}" name="parts_replaced" :value="old('parts_replaced', $maintenanceRecord->parts_replaced)"
                                rows="3" placeholder="{{ __('List any parts that were replaced...') }}" />
                        </div>

                        <!-- Next Maintenance Date -->
                        <div>
                            <x-forms.input type="date" label="{{ __('Next Maintenance Date') }}"
                                name="next_maintenance_date" :value="old(
                                    'next_maintenance_date',
                                    $maintenanceRecord->next_maintenance_date?->format('Y-m-d'),
                                )" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <x-fas-save class="h-5 w-5 mr-2" />
                    {{ __('Update Maintenance Record') }}
                </button>
                <a href="{{ route('maintenance-records.show', $maintenanceRecord) }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
