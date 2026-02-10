<x-layouts.app>
    <div x-data="{
        movementType: '{{ old('movement_type', $inventoryMovement->movement_type) }}',
        showFromLocation() {
            return ['CHECK_OUT', 'TRANSFER', 'RETURN'].includes(this.movementType);
        },
        showToLocation() {
            return ['CHECK_IN', 'TRANSFER', 'RETURN'].includes(this.movementType);
        }
    }">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('inventory-movements.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Movements') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Movement') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update movement details') }}</p>
        </div>

        <form method="POST" action="{{ route('inventory-movements.update', $inventoryMovement) }}" class="max-w-4xl">
            @csrf
            @method('PUT')

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
                        <!-- Item Selection (Read-only) -->
                        <div class="md:col-span-2">
                            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Item') }}
                            </label>
                            <div
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
                                @if ($inventoryMovement->item)
                                    {{ $inventoryMovement->item->name }} ({{ $inventoryMovement->item->code }})
                                @else
                                    -
                                @endif
                            </div>
                            <input type="hidden" name="item_id" value="{{ $inventoryMovement->item_id }}">
                        </div>

                        <!-- Movement Type (Read-only) -->
                        <div class="md:col-span-2">
                            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Movement Type') }}
                            </label>
                            <div
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
                                @php
                                    $enum = \App\Enums\MovementTypeEnum::tryFrom($inventoryMovement->movement_type);
                                @endphp
                                @if ($enum)
                                    <span class="inline-flex items-center">
                                        <x-dynamic-component :component="'fas-' . str_replace('fas-', '', $enum->icon())" class="h-4 w-4 mr-2" />
                                        {{ $enum->label() }}
                                    </span>
                                @else
                                    {{ $inventoryMovement->movement_type }}
                                @endif
                            </div>
                            <input type="hidden" name="movement_type" value="{{ $inventoryMovement->movement_type }}">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ __('Movement type cannot be changed after creation') }}
                            </p>
                        </div>

                        <!-- From Location (Read-only visual, editable for admin) -->
                        <div x-show="showFromLocation()" x-transition>
                            <x-select label="{{ __('From Location') }}" name="from_location_id" :options="$locations->pluck('name', 'id')->toArray()"
                                value="{{ old('from_location_id', $inventoryMovement->from_location_id) }}"
                                placeholder="{{ __('Select origin location') }}" />
                        </div>

                        <!-- To Location -->
                        <div x-show="showToLocation()" x-transition>
                            <x-select label="{{ __('To Location') }}" name="to_location_id" :options="$locations->pluck('name', 'id')->toArray()"
                                value="{{ old('to_location_id', $inventoryMovement->to_location_id) }}"
                                placeholder="{{ __('Select destination location') }}" />
                        </div>

                        <!-- Quantity -->
                        <div>
                            <x-forms.input type="number" label="{{ __('Quantity') }} *" name="quantity"
                                :value="old('quantity', $inventoryMovement->quantity)" min="1" required />
                        </div>

                        <!-- Performed At -->
                        <div>
                            <x-forms.input type="datetime-local" label="{{ __('Date & Time') }} *" name="performed_at"
                                :value="old('performed_at', $inventoryMovement->performed_at?->format('Y-m-d\TH:i'))" required />
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
                            <x-textarea label="{{ __('Notes') }}" name="notes" :value="old('notes', $inventoryMovement->notes)" rows="3"
                                placeholder="{{ __('Additional notes about this movement...') }}" />
                        </div>
                        <div>
                            <x-forms.input label="{{ __('Reason') }}" name="reason" :value="old('reason', $inventoryMovement->reason)"
                                placeholder="{{ __('e.g., Project assignment') }}" />
                        </div>
                        <div>
                            <x-forms.input label="{{ __('Reference Document') }}" name="reference_document"
                                :value="old('reference_document', $inventoryMovement->reference_document)" placeholder="{{ __('e.g., INV-2024-001') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <x-fas-save class="h-5 w-5 mr-2" />
                    {{ __('Update Movement') }}
                </button>
                <a href="{{ route('inventory-movements.show', $inventoryMovement) }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
