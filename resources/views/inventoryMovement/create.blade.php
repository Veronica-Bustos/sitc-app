<x-layouts.app>
    <div x-data="{
        movementType: '{{ old('movement_type', 'TRANSFER') }}',
        itemId: '{{ old('item_id', $preselectedItem?->id ?? '') }}',
        fromLocationId: '{{ old('from_location_id', $preselectedFromLocation?->id ?? '') }}',
        toLocationId: '{{ old('to_location_id', $preselectedToLocation?->id ?? '') }}',
    
        showFromLocation() {
            return ['CHECK_OUT', 'TRANSFER', 'RETURN'].includes(this.movementType);
        },
        showToLocation() {
            return ['CHECK_IN', 'TRANSFER', 'RETURN'].includes(this.movementType);
        },
        validate() {
            if (this.movementType === 'TRANSFER') {
                return this.fromLocationId && this.toLocationId && this.fromLocationId !== this.toLocationId;
            }
            return true;
        }
    }">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('inventory-movements.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Movements') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('New Movement') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Register a new inventory movement') }}</p>
        </div>

        <form method="POST" action="{{ route('inventory-movements.store') }}" class="max-w-4xl"
            @submit.prevent="if (validate()) $el.submit()">
            @csrf

            <!-- Movement Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Movement Information') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Basic movement details') }}</p>
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

                        <!-- Movement Type -->
                        <div class="md:col-span-2">
                            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Movement Type') }} *
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($movementTypes as $key => $label)
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="movement_type" value="{{ $key }}"
                                            x-model="movementType" class="sr-only">
                                        <div class="w-full rounded-lg border-2 p-3 transition-all"
                                            :class="movementType === '{{ $key }}'
                                                ?
                                                'border-blue-500 bg-blue-50 dark:bg-blue-900/20' :
                                                'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                            <div class="flex items-center">
                                                @php
                                                    $enum = \App\Enums\MovementTypeEnum::tryFrom($key);
                                                @endphp
                                                @if ($enum)
                                                    <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $enum->icon())" class="h-5 w-5 mr-2"
                                                        x-bind:class="movementType === '{{ $key }}'
                                                            ?
                                                            'text-blue-600 dark:text-blue-400' :
                                                            'text-gray-400'" />
                                                @endif
                                                <span class="text-sm font-medium"
                                                    :class="movementType === '{{ $key }}'
                                                        ?
                                                        'text-blue-900 dark:text-blue-100' :
                                                        'text-gray-700 dark:text-gray-300'">
                                                    {{ $label }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('movement_type')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- From Location -->
                        <div x-show="showFromLocation()" x-transition>
                            <x-select label="{{ __('From Location') }}" name="from_location_id" :options="$locations->pluck('name', 'id')->toArray()"
                                value="{{ $preselectedFromLocation?->id ?? old('from_location_id') }}"
                                placeholder="{{ __('Select origin location') }}" />
                        </div>

                        <!-- To Location -->
                        <div x-show="showToLocation()" x-transition>
                            <x-select label="{{ __('To Location') }}" name="to_location_id" :options="$locations->pluck('name', 'id')->toArray()"
                                value="{{ $preselectedToLocation?->id ?? old('to_location_id') }}"
                                placeholder="{{ __('Select destination location') }}" />
                        </div>

                        <!-- Quantity -->
                        <div>
                            <x-forms.input type="number" label="{{ __('Quantity') }} *" name="quantity"
                                :value="old('quantity', 1)" min="1" required />
                        </div>

                        <!-- Performed At -->
                        <div>
                            <x-forms.input type="datetime-local" label="{{ __('Date & Time') }} *" name="performed_at"
                                :value="old('performed_at', now()->format('Y-m-d\TH:i'))" required />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Additional Information') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Optional details') }}</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Notes') }}" name="notes" :value="old('notes')" rows="3"
                                placeholder="{{ __('Additional notes about this movement...') }}" />
                        </div>
                        <div>
                            <x-forms.input label="{{ __('Reason') }}" name="reason" :value="old('reason')"
                                placeholder="{{ __('e.g., Project assignment') }}" />
                        </div>
                        <div>
                            <x-forms.input label="{{ __('Reference Document') }}" name="reference_document"
                                :value="old('reference_document')" placeholder="{{ __('e.g., INV-2024-001') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <x-fas-save class="h-5 w-5 mr-2" />
                    {{ __('Register Movement') }}
                </button>
                <a href="{{ route('inventory-movements.index') }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
