<x-layouts.app>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('locations.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ __('Locations') }}
                </a>
                <span>/</span>
                <a href="{{ route('locations.show', $location) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ $location->name }}
                </a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ __('Edit') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Location') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update location information') }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('locations.update', $location) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Basic Information') }}
                </h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Code -->
                        <div>
                            <label for="code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Code') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                value="{{ old('code', $location->code) }}" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                                {{ $location->items()->count() > 0 ? 'readonly' : '' }}>
                            @if ($location->items()->count() > 0)
                                <p class="mt-1 text-xs text-amber-600">
                                    <x-fas-exclamation-triangle class="h-3 w-3 inline mr-1" />
                                    {{ __('Code cannot be changed while items exist at this location') }}
                                </p>
                            @endif
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $location->name) }}" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="WAREHOUSE"
                                {{ old('type', $location->type) == 'WAREHOUSE' ? 'selected' : '' }}>
                                {{ __('Warehouse') }}</option>
                            <option value="SITE" {{ old('type', $location->type) == 'SITE' ? 'selected' : '' }}>
                                {{ __('Work Site') }}</option>
                            <option value="OFFICE" {{ old('type', $location->type) == 'OFFICE' ? 'selected' : '' }}>
                                {{ __('Office') }}</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Address') }}
                        </label>
                        <textarea name="address" id="address" rows="2"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $location->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div>
                        <label for="coordinates"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('GPS Coordinates') }}
                        </label>
                        <input type="text" name="coordinates" id="coordinates"
                            value="{{ old('coordinates', $location->coordinates) }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                            placeholder="{{ __('e.g., -33.4569, -70.6483') }}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('Latitude, Longitude format') }}</p>
                        @error('coordinates')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Configuration') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Responsible User -->
                    <div>
                        <label for="responsible_user_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Responsible User') }}
                        </label>
                        <select name="responsible_user_id" id="responsible_user_id"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Select user') }}</option>
                            @foreach ($responsibles ?? [] as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('responsible_user_id', $location->responsible_user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsible_user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Location -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Parent Location') }}
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('None (Top Level)') }}</option>
                            @foreach ($parents ?? [] as $parent)
                                @if ($parent->id !== $location->id)
                                    <option value="{{ $parent->id }}"
                                        {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }} ({{ $parent->code }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('Cannot select self as parent') }}</p>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="ACTIVE"
                                {{ old('status', $location->status) == 'ACTIVE' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="INACTIVE"
                                {{ old('status', $location->status) == 'INACTIVE' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                            <option value="CLOSED"
                                {{ old('status', $location->status) == 'CLOSED' ? 'selected' : '' }}>
                                {{ __('Closed') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Start Date') }}
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $location->start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('End Date') }}
                        </label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', $location->end_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('Notes') }}
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $location->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <a href="{{ route('locations.show', $location) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    @can('delete', $location)
                        <button type="button" @click="$dispatch('open-delete-modal')"
                            class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 rounded-lg transition-colors">
                            <x-fas-trash class="h-4 w-4 mr-2" />
                            {{ __('Delete') }}
                        </button>
                    @endcan
                </div>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-save class="h-4 w-4 mr-2" />
                    {{ __('Update Location') }}
                </button>
            </div>
        </form>

        <!-- Delete Modal -->
        @can('delete', $location)
            <div x-data="{ open: false }" @open-delete-modal.window="open = true">
                <x-confirm-modal x-model="open" title="{{ __('Delete Location') }}"
                    message="{{ __('Are you sure you want to delete this location? This action cannot be undone. Items at this location will need to be reassigned.') }}"
                    confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                    @confirm="$refs.deleteForm.submit()">
                </x-confirm-modal>
                <form x-ref="deleteForm" method="POST" action="{{ route('locations.destroy', $location) }}"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endcan
    </div>
</x-layouts.app>
