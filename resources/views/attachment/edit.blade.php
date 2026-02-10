@php
    $attachableTypeLabel = match ($attachment->attachable_type) {
        \App\Models\Item::class => __('Item'),
        \App\Models\InventoryMovement::class => __('Movement'),
        \App\Models\MaintenanceRecord::class => __('Maintenance Record'),
        default => __('Unknown'),
    };

    $isImage = str_starts_with($attachment->mime_type, 'image/');
@endphp

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('attachments.show', $attachment) }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center mb-2">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Attachment') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Attachment') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $attachment->original_name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Preview -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('File Preview') }}</h3>

                    <div
                        class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4 min-h-[200px]">
                        @if ($isImage)
                            <img src="{{ route('attachments.preview', $attachment) }}"
                                alt="{{ $attachment->original_name }}"
                                class="max-h-[250px] max-w-full object-contain rounded">
                        @else
                            @php
                                $iconClass = str_contains($attachment->mime_type, 'pdf')
                                    ? 'fa-file-pdf text-red-500'
                                    : (str_contains($attachment->mime_type, 'word')
                                        ? 'fa-file-word text-blue-500'
                                        : (str_contains($attachment->mime_type, 'excel')
                                            ? 'fa-file-excel text-green-500'
                                            : 'fa-file text-gray-500'));
                            @endphp
                            <div class="text-center">
                                <i class="fas {{ $iconClass }} text-6xl mb-2"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ strtoupper(pathinfo($attachment->original_name, PATHINFO_EXTENSION)) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('Size') }}</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $attachment->size ? number_format($attachment->size / 1024, 2) . ' KB' : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('Type') }}</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $attachment->mime_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('Uploaded') }}</span>
                            <span
                                class="text-gray-900 dark:text-gray-100">{{ $attachment->created_at?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Edit Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('attachments.update', $attachment) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Metadata -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Metadata') }}</h3>

                        <div class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Description') }}
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="{{ __('Optional description for this file...') }}">{{ old('description', $attachment->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Display Order -->
                            <div>
                                <label for="order"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Display Order') }}
                                </label>
                                <input type="number" id="order" name="order"
                                    value="{{ old('order', $attachment->order) }}" min="0"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('order') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Lower numbers appear first when displaying multiple files') }}</p>
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Featured -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                    {{ old('is_featured', $attachment->is_featured) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Mark as Featured') }}
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Featured files are highlighted as the main image for the entity') }}</p>
                        </div>
                    </div>

                    <!-- Related Entity Info (Read-only) -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Related Entity') }}</h3>
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <x-fas-link class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $attachableTypeLabel }}</p>
                                @if ($attachment->attachable)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        @if ($attachment->attachable_type === \App\Models\Item::class)
                                            {{ $attachment->attachable->name }} ({{ $attachment->attachable->code }})
                                        @elseif ($attachment->attachable_type === \App\Models\InventoryMovement::class)
                                            {{ $attachment->attachable->item?->name }} -
                                            {{ $attachment->attachable->movement_type }}
                                        @elseif ($attachment->attachable_type === \App\Models\MaintenanceRecord::class)
                                            {{ $attachment->attachable->item?->name }} -
                                            {{ $attachment->attachable->type }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            {{ __('The related entity cannot be changed. Upload a new file if needed.') }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('attachments.show', $attachment) }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                            <x-fas-save class="h-5 w-5 mr-2" />
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
