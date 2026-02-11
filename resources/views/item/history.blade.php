<x-layouts.app>
    <div>
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('items.show', $item) }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Item') }}
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ __('Movement History') }} - {{ $item->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $item->code }}</code>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @can('create', App\Models\InventoryMovement::class)
                        <a href="{{ route('inventory-movements.create', ['item' => $item->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <x-fas-plus class="h-4 w-4 mr-2" />
                            {{ __('New Movement') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @if ($movements->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                    <div class="space-y-8">
                        @foreach ($movements as $movement)
                            <div class="relative flex gap-6">
                                <!-- Icon -->
                                <div class="relative shrink-0">
                                    @php
                                        $iconColors = [
                                            'check_in' =>
                                                'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400',
                                            'check_out' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400',
                                            'transfer' =>
                                                'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400',
                                            'return' =>
                                                'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400',
                                            'audit_adjustment' =>
                                                'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                            'disposal' =>
                                                'bg-gray-700 text-gray-100 dark:bg-gray-900 dark:text-gray-400',
                                        ];
                                        $icons = [
                                            'check_in' => 'arrow-down',
                                            'check_out' => 'arrow-up',
                                            'transfer' => 'exchange-alt',
                                            'return' => 'undo',
                                            'audit_adjustment' => 'balance-scale',
                                            'disposal' => 'trash',
                                        ];
                                    @endphp
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center {{ $iconColors[$movement->movement_type] ?? $iconColors['check_in'] }}">
                                        <x-dynamic-component
                                            component="fas-{{ $icons[$movement->movement_type] ?? 'circle' }}"
                                            class="h-5 w-5" />
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 pt-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
                                        <x-movement-type-badge :type="$movement->movement_type" />
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <x-fas-user class="h-4 w-4 text-gray-400" />
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $movement->user?->name ?? __('Unknown') }}
                                            </span>
                                        </div>

                                        @if ($movement->fromLocation || $movement->toLocation)
                                            <div class="flex items-center gap-2 mb-2">
                                                <x-fas-map-marker-alt class="h-4 w-4 text-gray-400" />
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    @if ($movement->fromLocation && $movement->toLocation)
                                                        {{ $movement->fromLocation->name }}
                                                        <x-fas-arrow-right class="h-3 w-3 inline mx-1 text-gray-400" />
                                                        {{ $movement->toLocation->name }}
                                                    @elseif($movement->toLocation)
                                                        {{ __('To') }} {{ $movement->toLocation->name }}
                                                    @elseif($movement->fromLocation)
                                                        {{ __('From') }} {{ $movement->fromLocation->name }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif

                                        @if ($movement->quantity > 1)
                                            <div class="flex items-center gap-2 mb-2">
                                                <x-fas-hashtag class="h-4 w-4 text-gray-400" />
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('Quantity') }}: {{ $movement->quantity }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($movement->reference_document)
                                            <div class="flex items-center gap-2 mb-2">
                                                <x-fas-file-alt class="h-4 w-4 text-gray-400" />
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('Reference') }}: {{ $movement->reference_document }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($movement->notes)
                                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $movement->notes }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($movement->attachments->count() > 0)
                                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                    {{ __('Attachments') }}:</p>
                                                <div class="flex gap-2">
                                                    @foreach ($movement->attachments as $attachment)
                                                        <a href="{{ Storage::url($attachment->file_path) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500">
                                                            <x-fas-paperclip class="h-3 w-3 mr-1" />
                                                            {{ Str::limit($attachment->original_name, 15) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if ($movements->hasPages())
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        {{ $movements->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <x-fas-history class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
                    <p class="text-gray-500 dark:text-gray-400">{{ __('No movements recorded for this item') }}</p>
                    @can('create', App\Models\InventoryMovement::class)
                        <a href="{{ route('inventory-movements.create', ['item' => $item->id]) }}"
                            class="inline-flex items-center mt-4 text-blue-600 dark:text-blue-400 hover:underline">
                            <x-fas-plus class="h-4 w-4 mr-1" />
                            {{ __('Register first movement') }}
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
