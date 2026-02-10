<x-layouts.app>
    <div x-data="{ activeSection: 'basic' }">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('items.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm mb-2 inline-flex items-center">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Items') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('New Item') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create a new inventory item') }}</p>
        </div>

        <form method="POST" action="{{ route('items.store') }}" class="max-w-4xl">
            @csrf

            <!-- Section 1: Basic Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <button type="button" @click="activeSection = activeSection === 'basic' ? null : 'basic'"
                    class="w-full px-6 py-4 flex items-center justify-between text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                            <x-fas-info class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Basic Information') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Required item details') }}</p>
                        </div>
                    </div>
                    <x-fas-chevron-down class="h-5 w-5 text-gray-400 transition-transform"
                        x-bind:class="{ 'rotate-180': activeSection === 'basic' }" />
                </button>
                <div x-show="activeSection === 'basic'" x-transition
                    class="px-6 pb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-forms.input label="{{ __('Code') }} *" name="code" :value="old('code')" required />
                        <x-forms.input label="{{ __('Name') }} *" name="name" :value="old('name')" required />
                        <div class="md:col-span-2">
                            <x-textarea label="{{ __('Description') }}" name="description" :value="old('description')"
                                rows="3" />
                        </div>
                        <x-select label="{{ __('Category') }} *" name="category_id" :options="$categories->pluck('name', 'id')->toArray()" :value="old('category_id')"
                            required />
                    </div>
                </div>
            </div>

            <!-- Section 2: Location & Status -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <button type="button" @click="activeSection = activeSection === 'location' ? null : 'location'"
                    class="w-full px-6 py-4 flex items-center justify-between text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                            <x-fas-map-marker-alt class="h-5 w-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Location & Status') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Where is the item and its current state') }}</p>
                        </div>
                    </div>
                    <x-fas-chevron-down class="h-5 w-5 text-gray-400 transition-transform"
                        x-bind:class="{ 'rotate-180': activeSection === 'location' }" />
                </button>
                <div x-show="activeSection === 'location'" x-transition
                    class="px-6 pb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-select label="{{ __('Initial Location') }}" name="current_location_id" :options="$locations->pluck('name', 'id')->toArray()"
                            :value="old('current_location_id')" placeholder="{{ __('Select a location') }}" />
                        <x-select label="{{ __('Status') }} *" name="status" :options="$statuses" :value="old('status', 'available')"
                            required />
                        <x-select label="{{ __('Condition') }} *" name="condition" :options="$conditions" :value="old('condition', 'good')"
                            required />
                    </div>
                </div>
            </div>

            <!-- Section 3: Purchase Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <button type="button" @click="activeSection = activeSection === 'purchase' ? null : 'purchase'"
                    class="w-full px-6 py-4 flex items-center justify-between text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-lg">
                            <x-fas-dollar-sign class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Purchase Information') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Acquisition details') }}</p>
                        </div>
                    </div>
                    <x-fas-chevron-down class="h-5 w-5 text-gray-400 transition-transform"
                        x-bind:class="{ 'rotate-180': activeSection === 'purchase' }" />
                </button>
                <div x-show="activeSection === 'purchase'" x-transition
                    class="px-6 pb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-forms.input type="date" label="{{ __('Purchase Date') }}" name="purchase_date"
                            :value="old('purchase_date')" />
                        <x-forms.input type="number" step="0.01" label="{{ __('Purchase Price') }}"
                            name="purchase_price" :value="old('purchase_price')" placeholder="0.00" />
                        <x-forms.input type="number" step="0.01" label="{{ __('Current Value') }}"
                            name="current_value" :value="old('current_value')" placeholder="0.00" />
                        <x-forms.input label="{{ __('Supplier') }}" name="supplier" :value="old('supplier')" />
                    </div>
                </div>
            </div>

            <!-- Section 4: Technical Specifications -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <button type="button" @click="activeSection = activeSection === 'technical' ? null : 'technical'"
                    class="w-full px-6 py-4 flex items-center justify-between text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg">
                            <x-fas-cog class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Technical Specifications') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Product details and specifications') }}</p>
                        </div>
                    </div>
                    <x-fas-chevron-down class="h-5 w-5 text-gray-400 transition-transform"
                        x-bind:class="{ 'rotate-180': activeSection === 'technical' }" />
                </button>
                <div x-show="activeSection === 'technical'" x-transition
                    class="px-6 pb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-forms.input label="{{ __('Serial Number') }}" name="serial_number" :value="old('serial_number')" />
                        <x-forms.input label="{{ __('Brand') }}" name="brand" :value="old('brand')" />
                        <x-forms.input label="{{ __('Model') }}" name="model" :value="old('model')" />
                        <x-forms.input label="{{ __('Barcode') }}" name="barcode" :value="old('barcode')" />
                        <x-forms.input type="number" step="0.01" label="{{ __('Weight (kg)') }}"
                            name="weight_kg" :value="old('weight_kg')" />
                        <x-forms.input label="{{ __('Dimensions') }}" name="dimensions" :value="old('dimensions')"
                            placeholder="{{ __('L x W x H') }}" />
                    </div>
                </div>
            </div>

            <!-- Section 5: Configuration -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <button type="button" @click="activeSection = activeSection === 'config' ? null : 'config'"
                    class="w-full px-6 py-4 flex items-center justify-between text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                            <x-fas-sliders-h class="h-5 w-5 text-gray-600 dark:text-gray-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Configuration') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Additional settings') }}</p>
                        </div>
                    </div>
                    <x-fas-chevron-down class="h-5 w-5 text-gray-400 transition-transform"
                        x-bind:class="{ 'rotate-180': activeSection === 'config' }" />
                </button>
                <div x-show="activeSection === 'config'" x-transition
                    class="px-6 pb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-forms.input type="number" label="{{ __('Minimum Stock') }}" name="minimum_stock"
                            :value="old('minimum_stock', 0)" />
                        <x-forms.input label="{{ __('Unit of Measure') }}" name="unit_of_measure" :value="old('unit_of_measure')"
                            placeholder="{{ __('e.g., unit, kg, liter') }}" />
                        <x-forms.input type="date" label="{{ __('Warranty Expiry') }}" name="warranty_expiry"
                            :value="old('warranty_expiry')" />
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <x-fas-save class="h-5 w-5 mr-2" />
                    {{ __('Create Item') }}
                </button>
                <a href="{{ route('items.index') }}"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
