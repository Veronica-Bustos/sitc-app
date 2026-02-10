<x-layouts.app>
    <div x-data="{ activeTab: 'general' }">
        <!-- Header with Navigation -->
        <div class="mb-6">
            <a href="{{ route('items.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Items') }}
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <code
                            class="text-lg font-mono bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded">{{ $item->code }}</code>
                        <x-status-badge :status="$item->status" />
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $item->name }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('items.history', $item) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <x-fas-history class="h-4 w-4 mr-2" />
                        {{ __('History') }}
                    </a>
                    @can('update', $item)
                        <a href="{{ route('items.edit', $item) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            <x-fas-edit class="h-4 w-4 mr-2" />
                            {{ __('Edit') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Quick Actions') }}</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('inventory-movements.create', ['item' => $item->id]) }}"
                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                    <x-fas-exchange-alt class="h-4 w-4 mr-2" />
                    {{ __('Register Movement') }}
                </a>
                <a href="{{ route('maintenance-records.create', ['item' => $item->id]) }}"
                    class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition-colors">
                    <x-fas-wrench class="h-4 w-4 mr-2" />
                    {{ __('New Maintenance') }}
                </a>
                <a href="{{ route('attachments.create', ['item' => $item->id]) }}"
                    class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                    <x-fas-paperclip class="h-4 w-4 mr-2" />
                    {{ __('Add Attachment') }}
                </a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'general'"
                        :class="{
                            'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'general',
                            'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'general'
                        }"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                        {{ __('General') }}
                    </button>
                    <button @click="activeTab = 'movements'"
                        :class="{
                            'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'movements',
                            'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'movements'
                        }"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                        {{ __('Recent Movements') }}
                    </button>
                    <button @click="activeTab = 'files'"
                        :class="{
                            'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'files',
                            'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'files'
                        }"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                        {{ __('Attachments') }}
                    </button>
                </nav>
            </div>

            <!-- Tab Content: General -->
            <div x-show="activeTab === 'general'" x-transition class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                {{ __('Basic Information') }}
                            </h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Category') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->category?->name ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Description') }}</dt>
                                    <dd class="text-gray-900 dark:text-gray-100 text-right max-w-xs">
                                        {{ $item->description ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Condition') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100 capitalize">
                                        {{ __('condition.' . $item->condition) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                {{ __('Location') }}
                            </h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Current Location') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->currentLocation?->name ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                {{ __('Purchase Information') }}
                            </h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Purchase Date') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->purchase_date?->format('d/m/Y') ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Purchase Price') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        ${{ number_format($item->purchase_price ?? 0, 2) }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Current Value') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        ${{ number_format($item->current_value ?? 0, 2) }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Supplier') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->supplier ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                {{ __('Technical Specifications') }}
                            </h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Serial Number') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->serial_number ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Brand') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->brand ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-600 dark:text-gray-400">{{ __('Model') }}</dt>
                                    <dd class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->model ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Movements -->
            <div x-show="activeTab === 'movements'" x-transition class="p-6">
                @if ($item->inventoryMovements->count() > 0)
                    <div class="space-y-4">
                        @foreach ($item->inventoryMovements as $movement)
                            <div
                                class="flex flex-row-reverse gap-4 pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="shrink-0">
                                    <x-movement-type-badge :type="$movement->movement_type" size="sm" />
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $movement->created_at->format('d/m/Y H:i') }}</span>
                                        <span>•</span>
                                        <span>{{ $movement->user?->name ?? '-' }}</span>
                                    </div>
                                    <p class="mt-1 text-gray-700 dark:text-gray-300">
                                        @if ($movement->fromLocation && $movement->toLocation)
                                            {{ __('From') }} <strong>{{ $movement->fromLocation->name }}</strong>
                                            {{ __('to') }} <strong>{{ $movement->toLocation->name }}</strong>
                                        @elseif($movement->toLocation)
                                            {{ __('To') }} <strong>{{ $movement->toLocation->name }}</strong>
                                        @elseif($movement->fromLocation)
                                            {{ __('From') }} <strong>{{ $movement->fromLocation->name }}</strong>
                                        @endif
                                    </p>
                                    @if ($movement->notes)
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('items.history', $item) }}"
                            class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ __('View full history') }} →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No movements recorded') }}</p>
                @endif
            </div>

            <!-- Tab Content: Attachments -->
            <div x-show="activeTab === 'files'" x-transition class="p-6">
                @if ($item->attachments->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        @foreach ($item->attachments as $attachment)
                            <div class="group relative">
                                @if (str_starts_with($attachment->mime_type, 'image/'))
                                    <img src="{{ Storage::url($attachment->file_path) }}"
                                        alt="{{ $attachment->original_name }}"
                                        class="w-full h-24 object-cover rounded-lg">
                                @else
                                    <div
                                        class="w-full h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <x-fas-file class="h-8 w-8 text-gray-400" />
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                        class="text-white p-2 hover:text-blue-300">
                                        <x-fas-eye class="h-5 w-5" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No attachments') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
