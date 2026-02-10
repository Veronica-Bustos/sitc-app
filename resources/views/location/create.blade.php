<x-layouts.app>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('locations.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ __('Locations') }}
                </a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ __('New Location') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Location') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create a new storage location or work site') }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('locations.store') }}" method="POST" class="space-y-6">
            @csrf

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
                            <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                                placeholder="{{ __('e.g., BOD-001') }}">
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
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="{{ __('e.g., Main Warehouse') }}">
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
                            <option value="">{{ __('Select type') }}</option>
                            <option value="WAREHOUSE" {{ old('type') == 'WAREHOUSE' ? 'selected' : '' }}>
                                {{ __('Warehouse') }}</option>
                            <option value="SITE" {{ old('type') == 'SITE' ? 'selected' : '' }}>{{ __('Work Site') }}
                            </option>
                            <option value="OFFICE" {{ old('type') == 'OFFICE' ? 'selected' : '' }}>{{ __('Office') }}
                            </option>
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
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('Full address...') }}">{{ old('address') }}</textarea>
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
                        <input type="text" name="coordinates" id="coordinates" value="{{ old('coordinates') }}"
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
                                    {{ old('responsible_user_id') == $user->id ? 'selected' : '' }}>
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
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} ({{ $parent->code }})
                                </option>
                            @endforeach
                        </select>
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
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                            <option value="CLOSED" {{ old('status') == 'CLOSED' ? 'selected' : '' }}>
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
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
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
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
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
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('Additional notes...') }}">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('locations.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-save class="h-4 w-4 mr-2" />
                    {{ __('Create Location') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
