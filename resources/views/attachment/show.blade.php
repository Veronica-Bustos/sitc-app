@php
    $isImage = str_starts_with($attachment->mime_type, 'image/');
    $isPdf = $attachment->mime_type === 'application/pdf';
    $iconClass = $isImage
        ? 'fa-image text-purple-500'
        : ($isPdf
            ? 'fa-file-pdf text-red-500'
            : (str_contains($attachment->mime_type, 'word')
                ? 'fa-file-word text-blue-500'
                : (str_contains($attachment->mime_type, 'excel')
                    ? 'fa-file-excel text-green-500'
                    : (str_contains($attachment->mime_type, 'video')
                        ? 'fa-video text-orange-500'
                        : (str_contains($attachment->mime_type, 'audio')
                            ? 'fa-music text-pink-500'
                            : 'fa-file text-gray-500')))));

    $attachableTypeLabel = match ($attachment->attachable_type) {
        \App\Models\Item::class => __('Item'),
        \App\Models\InventoryMovement::class => __('Movement'),
        \App\Models\MaintenanceRecord::class => __('Maintenance Record'),
        default => __('Unknown'),
    };

    $attachableRoute = match ($attachment->attachable_type) {
        \App\Models\Item::class => $attachment->attachable ? route('items.show', $attachment->attachable) : null,
        \App\Models\InventoryMovement::class => $attachment->attachable
            ? route('inventory-movements.show', $attachment->attachable)
            : null,
        \App\Models\MaintenanceRecord::class => $attachment->attachable
            ? route('maintenance-records.show', $attachment->attachable)
            : null,
        default => null,
    };
@endphp

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('attachments.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center mb-2">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Attachments') }}
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 truncate"
                    title="{{ $attachment->original_name }}">
                    {{ $attachment->original_name }}
                </h1>
                <div class="flex items-center gap-2">
                    @can('download', $attachment)
                        <a href="{{ route('attachments.download', $attachment) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <x-fas-download class="h-5 w-5 mr-2" />
                            {{ __('Download') }}
                        </a>
                    @endcan
                    @can('update', $attachment)
                        <a href="{{ route('attachments.edit', $attachment) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <x-fas-edit class="h-5 w-5 mr-2" />
                            {{ __('Edit') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Preview -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    @if ($isImage)
                        <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4 min-h-[400px]">
                            <img src="{{ route('attachments.preview', $attachment) }}"
                                alt="{{ $attachment->original_name }}"
                                class="max-h-[500px] max-w-full object-contain rounded shadow-lg cursor-zoom-in"
                                onclick="window.open(this.src, '_blank')" title="{{ __('Click to view full size') }}">
                        </div>
                    @elseif ($isPdf)
                        <div class="p-4">
                            <div
                                class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg p-8 mb-4">
                                <i class="fas fa-file-pdf text-red-500 text-6xl"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __('PDF Preview not available. Please download to view.') }}</p>
                                @can('download', $attachment)
                                    <a href="{{ route('attachments.download', $attachment) }}"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                        target="_blank">
                                        <x-fas-external-link-alt class="h-5 w-5 mr-2" />
                                        {{ __('Open in Browser') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg p-12">
                            <div class="text-center">
                                <i class="fas {{ $iconClass }} text-8xl mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ __('Preview not available for this file type') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- File Info Card -->
                <div
                    class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('File Information') }}
                    </h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('File Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 truncate"
                                title="{{ $attachment->original_name }}">{{ $attachment->original_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('File Type') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $attachment->mime_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Size') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $attachment->size ? number_format($attachment->size / 1024, 2) . ' KB' : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Extension') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 uppercase">
                                {{ pathinfo($attachment->original_name, PATHINFO_EXTENSION) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Uploaded') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $attachment->created_at?->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $attachment->uploader?->name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right Column - Metadata -->
            <div class="space-y-6">
                <!-- Related Entity -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Related To') }}</h3>

                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <x-fas-link class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attachableTypeLabel }}
                            </p>
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
                                @if ($attachableRoute)
                                    <a href="{{ $attachableRoute }}"
                                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-1 inline-flex items-center">
                                        {{ __('View Entity') }}
                                        <x-fas-external-link-alt class="h-3 w-3 ml-1" />
                                    </a>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Entity not found') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Options -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Options') }}</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Featured') }}</span>
                            @if ($attachment->is_featured)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <x-fas-star class="h-3 w-3 mr-1" />
                                    {{ __('Yes') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ __('No') }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Display Order') }}</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attachment->order }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Storage') }}</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-gray-100 uppercase">{{ $attachment->disk }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if ($attachment->description)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Description') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                            {{ $attachment->description }}</p>
                    </div>
                @endif

                <!-- Actions -->
                @can('delete', $attachment)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-2">{{ __('Danger Zone') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('Once deleted, this file cannot be recovered.') }}</p>
                        <form action="{{ route('attachments.destroy', $attachment) }}" method="POST"
                            onsubmit="return confirm('{{ __('Are you sure you want to delete this file? This action cannot be undone.') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                <x-fas-trash class="h-5 w-5 mr-2" />
                                {{ __('Delete File') }}
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>
