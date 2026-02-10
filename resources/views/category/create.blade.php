<x-layouts.app>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('categories.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ __('Categories') }}
                </a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ __('New Category') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Category') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Create a new item category') }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Basic Information') }}
                </h2>

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('e.g., Power Tools') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Slug') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('e.g., power-tools') }}">
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('URL-friendly identifier. Auto-generated from name if empty.') }}</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('Category description...') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Appearance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Appearance') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Color') }}
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}"
                                class="h-10 w-20 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                            <input type="text" name="color_text" id="color_text"
                                value="{{ old('color', '#3B82F6') }}"
                                class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-mono text-sm">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ __('Color for UI badges and indicators') }}</p>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Icon') }}
                        </label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('e.g., fas-hammer') }}">
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('FontAwesome class (e.g., fas-hammer, fas-tools)') }}</p>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Configuration') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Parent Category') }}
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('None (Top Level)') }}</option>
                            @foreach ($parents ?? [] as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center h-full pt-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Active') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-save class="h-4 w-4 mr-2" />
                    {{ __('Create Category') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function() {
                if (!slugInput.dataset.edited) {
                    slugInput.value = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            });

            slugInput.addEventListener('input', function() {
                this.dataset.edited = 'true';
            });

            // Sync color picker with text input
            const colorPicker = document.getElementById('color');
            const colorText = document.getElementById('color_text');

            colorPicker.addEventListener('input', function() {
                colorText.value = this.value;
            });

            colorText.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    colorPicker.value = this.value;
                }
            });
        </script>
    @endpush
</x-layouts.app>
