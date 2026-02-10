<x-layouts.app>
    @php
        $typeEnum = \App\Enums\MaintenanceTypeEnum::tryFrom($maintenanceRecord->type);
        $statusEnum = \App\Enums\MaintenanceStatusEnum::tryFrom($maintenanceRecord->status);
        $priorityEnum = \App\Enums\MaintenancePriorityEnum::tryFrom($maintenanceRecord->priority);
    @endphp
    <div x-data="{ activeTab: 'general' }">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('maintenance-records.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Maintenance Records') }}
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ __('Maintenance Record') }} #{{ $maintenanceRecord->id }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $maintenanceRecord->request_date?->format('d/m/Y') ?? '-' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($statusEnum)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusEnum->color() }}">
                            {{ $statusEnum->label() }}
                        </span>
                    @endif
                    @if ($priorityEnum)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $priorityEnum->color() }}">
                            <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $priorityEnum->icon())" class="h-4 w-4 mr-2" />
                            {{ $priorityEnum->label() }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div
            class="bg-white dark:bg-gray-800 rounded-t-lg shadow-sm border border-gray-200 dark:border-gray-700 border-b-0">
            <div class="px-6">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'general'"
                        :class="activeTab === 'general'
                            ?
                            'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        {{ __('General Information') }}
                    </button>
                    @if ($maintenanceRecord->diagnosis || $maintenanceRecord->actions_taken)
                        <button @click="activeTab = 'work'"
                            :class="activeTab === 'work'
                                ?
                                'border-blue-500 text-blue-600 dark:text-blue-400' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            {{ __('Work Details') }}
                        </button>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- General Tab -->
            <div x-show="activeTab === 'general'" x-transition class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Item Card -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Item') }}
                            </h3>
                            @if ($maintenanceRecord->item)
                                <div class="flex items-start gap-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg flex-shrink-0">
                                        <x-fas-box class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('items.show', $maintenanceRecord->item) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $maintenanceRecord->item->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span
                                                class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $maintenanceRecord->item->code }}</span>
                                        </p>
                                        @if ($maintenanceRecord->item->category)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                                {{ __('Category') }}: {{ $maintenanceRecord->item->category->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Item not found') }}</p>
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Description') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{ $maintenanceRecord->description ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Details') }}
                            </h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Type') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if ($typeEnum)
                                            <span class="inline-flex items-center">
                                                <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $typeEnum->icon())"
                                                    class="h-4 w-4 mr-2 text-gray-400" />
                                                {{ $typeEnum->label() }}
                                            </span>
                                        @else
                                            {{ $maintenanceRecord->type }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Estimated Cost') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $maintenanceRecord->cost ? number_format($maintenanceRecord->cost, 2) : '-' }}
                                    </dd>
                                </div>
                                @if ($maintenanceRecord->next_maintenance_date)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Next Maintenance') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $maintenanceRecord->next_maintenance_date->format('d/m/Y') }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- People Card -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('People') }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Requested By') }}</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $maintenanceRecord->requester?->name ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Assigned Technician') }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $maintenanceRecord->technician?->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Dates Card -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Dates') }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Request Date') }}</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $maintenanceRecord->request_date?->format('d/m/Y') ?? '-' }}
                                    </p>
                                </div>
                                @if ($maintenanceRecord->intervention_date)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('Intervention Date') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $maintenanceRecord->intervention_date->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                                @if ($maintenanceRecord->completion_date)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Completion Date') }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $maintenanceRecord->completion_date->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3">
                            @can('update', $maintenanceRecord)
                                <a href="{{ route('maintenance-records.edit', $maintenanceRecord) }}"
                                    class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                    <x-fas-edit class="h-4 w-4 mr-2" />
                                    {{ __('Edit Record') }}
                                </a>
                            @endcan
                            <a href="{{ route('items.show', $maintenanceRecord->item) }}"
                                class="flex items-center justify-center w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <x-fas-eye class="h-4 w-4 mr-2" />
                                {{ __('View Item') }}
                            </a>
                            @can('delete', $maintenanceRecord)
                                <form method="POST"
                                    action="{{ route('maintenance-records.destroy', $maintenanceRecord) }}"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this maintenance record?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                        <x-fas-trash class="h-4 w-4 mr-2" />
                                        {{ __('Delete Record') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Details Tab -->
            <div x-show="activeTab === 'work'" x-transition class="p-6" style="display: none;">
                <div class="space-y-6">
                    @if ($maintenanceRecord->diagnosis)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Diagnosis') }}
                            </h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300">{{ $maintenanceRecord->diagnosis }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($maintenanceRecord->actions_taken)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Actions Taken') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300">{{ $maintenanceRecord->actions_taken }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($maintenanceRecord->parts_replaced)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Parts Replaced') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300">{{ $maintenanceRecord->parts_replaced }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
