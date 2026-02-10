<x-layouts.app>
    @php
        $typeEnum = \App\Enums\MovementTypeEnum::tryFrom($inventoryMovement->movement_type);
    @endphp
    <div>
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('inventory-movements.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Movements') }}
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ __('Movement') }} #{{ $inventoryMovement->id }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $inventoryMovement->performed_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($typeEnum)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $typeEnum->color() }}">
                            <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $typeEnum->icon())" class="h-4 w-4 mr-2" />
                            {{ $typeEnum->label() }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Item Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Item') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        @if ($inventoryMovement->item)
                            <div class="flex items-start gap-4">
                                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg flex-shrink-0">
                                    <x-fas-box class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('items.show', $inventoryMovement->item) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $inventoryMovement->item->name }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span
                                            class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $inventoryMovement->item->code }}</span>
                                    </p>
                                    @if ($inventoryMovement->item->category)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            {{ __('Category') }}: {{ $inventoryMovement->item->category->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Item not found') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Locations Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Location Transfer') }}
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex items-center gap-4">
                            <!-- From Location -->
                            <div class="flex-1 text-center">
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                    <x-fas-map-marker-alt
                                        class="h-8 w-8 text-gray-500 dark:text-gray-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('From') }}</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $inventoryMovement->fromLocation?->name ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Arrow -->
                            <div class="flex-shrink-0">
                                @if ($typeEnum)
                                    <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $typeEnum->icon())"
                                        class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                @else
                                    <x-fas-arrow-right class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                @endif
                            </div>

                            <!-- To Location -->
                            <div class="flex-1 text-center">
                                <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-4">
                                    <x-fas-map-marker-alt
                                        class="h-8 w-8 text-green-600 dark:text-green-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('To') }}</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $inventoryMovement->toLocation?->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Quantity') }}:
                            </span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $inventoryMovement->quantity }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Details') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($inventoryMovement->reason)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Reason') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $inventoryMovement->reason }}</dd>
                                </div>
                            @endif
                            @if ($inventoryMovement->reference_document)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Reference Document') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $inventoryMovement->reference_document }}</dd>
                                </div>
                            @endif
                            @if ($inventoryMovement->notes)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Notes') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $inventoryMovement->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Registered By') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 flex-shrink-0">
                                <x-fas-user class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $inventoryMovement->user?->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $inventoryMovement->user?->email ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Actions') }}</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @can('update', $inventoryMovement)
                            <a href="{{ route('inventory-movements.edit', $inventoryMovement) }}"
                                class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                <x-fas-edit class="h-4 w-4 mr-2" />
                                {{ __('Edit Movement') }}
                            </a>
                        @endcan
                        <a href="{{ route('items.show', $inventoryMovement->item) }}"
                            class="flex items-center justify-center w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <x-fas-eye class="h-4 w-4 mr-2" />
                            {{ __('View Item') }}
                        </a>
                        @can('delete', $inventoryMovement)
                            <form method="POST" action="{{ route('inventory-movements.destroy', $inventoryMovement) }}"
                                onsubmit="return confirm('{{ __('Are you sure you want to delete this movement?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    <x-fas-trash class="h-4 w-4 mr-2" />
                                    {{ __('Delete Movement') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
