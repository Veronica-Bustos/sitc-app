<x-layouts.app>
    <div x-data="{
        deleteModal: false,
        attachmentToDelete: null,
        attachmentIdToDelete: '',
        attachmentNameToDelete: '',
        previewModal: false,
        previewAttachment: null,
        confirmDelete(attachment) {
            this.attachmentToDelete = attachment;
            this.attachmentIdToDelete = attachment.id;
            this.attachmentNameToDelete = attachment.original_name;
            this.deleteModal = true;
        },
        openPreview(attachment) {
            this.previewAttachment = attachment;
            this.previewModal = true;
        }
    }">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Attachments') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage files and documents') }}</p>
            </div>
            @can('create', App\Models\Attachment::class)
                <a href="{{ route('attachments.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-plus class="h-5 w-5 mr-2" />
                    {{ __('Upload Files') }}
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <x-filters action="{{ route('attachments.index') }}" :has-filters="request()->hasAny([
            'search',
            'mime_type',
            'attachable_type',
            'uploader',
            'featured',
            'date_from',
            'date_to',
        ])" :active-filters-count="count(array_filter(request()->only(['mime_type', 'attachable_type', 'uploader', 'featured', 'date_from', 'date_to']), fn($v) => $v !== null && $v !== ''))"
            search-placeholder="{{ __('Search by filename or description...') }}" search-value="{{ request('search') }}">
            <x-select name="mime_type" :options="$mimeTypes" :value="request('mime_type')" placeholder="{{ __('All File Types') }}" />
            <x-select name="attachable_type" :options="$attachableTypes" :value="request('attachable_type')"
                placeholder="{{ __('All Entities') }}" />
            <x-select name="uploader" :options="$uploaders->pluck('name', 'id')->toArray()" :value="request('uploader')" placeholder="{{ __('All Uploaders') }}" />
            <x-select name="featured" :options="['1' => __('Featured Only'), '0' => __('Not Featured')]" :value="request('featured')" placeholder="{{ __('All Files') }}" />
            <div class="grid grid-cols-2 gap-2">
                <x-forms.input type="date" name="date_from" :value="request('date_from')" label="{{ __('From') }}" />
                <x-forms.input type="date" name="date_to" :value="request('date_to')" label="{{ __('To') }}" />
            </div>
        </x-filters>

        <!-- Attachments Grid -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if ($attachments->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
                    @foreach ($attachments as $attachment)
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
                        @endphp
                        <div
                            class="group relative bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Preview/Icon -->
                            <div class="aspect-square flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                @if ($isImage)
                                    <img src="{{ route('attachments.preview', $attachment) }}"
                                        alt="{{ $attachment->original_name }}"
                                        class="w-full h-full object-cover cursor-pointer"
                                        @click="openPreview({{ json_encode(['id' => $attachment->id, 'original_name' => $attachment->original_name, 'mime_type' => $attachment->mime_type, 'description' => $attachment->description, 'uploader' => $attachment->uploader?->name, 'created_at' => $attachment->created_at?->format('d/m/Y H:i')]) }})"
                                        loading="lazy">
                                @else
                                    <div class="text-center p-4">
                                        <i class="fas {{ $iconClass }} text-5xl mb-2"></i>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-full">
                                            {{ strtoupper(pathinfo($attachment->original_name, PATHINFO_EXTENSION)) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Overlay -->
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all flex flex-col justify-end">
                                <div
                                    class="p-3 opacity-0 group-hover:opacity-100 transition-opacity text-white transform translate-y-2 group-hover:translate-y-0">
                                    <p class="font-medium text-sm truncate" title="{{ $attachment->original_name }}">
                                        {{ $attachment->original_name }}
                                    </p>
                                    <p class="text-xs text-gray-300">
                                        {{ $attachment->uploader?->name ?? '-' }} •
                                        {{ $attachment->created_at?->format('d/m/Y') }}
                                    </p>
                                    @if ($attachment->is_featured)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-yellow-500 text-white mt-1">
                                            <x-fas-star class="h-3 w-3 mr-1" />
                                            {{ __('Featured') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div
                                class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @can('view', $attachment)
                                    <a href="{{ route('attachments.show', $attachment) }}"
                                        class="p-1.5 bg-white dark:bg-gray-800 rounded-full text-gray-600 dark:text-gray-300 hover:text-blue-600 shadow-sm"
                                        title="{{ __('View') }}">
                                        <x-fas-eye class="h-4 w-4" />
                                    </a>
                                @endcan
                                @can('download', $attachment)
                                    <a href="{{ route('attachments.download', $attachment) }}"
                                        class="p-1.5 bg-white dark:bg-gray-800 rounded-full text-gray-600 dark:text-gray-300 hover:text-green-600 shadow-sm"
                                        title="{{ __('Download') }}">
                                        <x-fas-download class="h-4 w-4" />
                                    </a>
                                @endcan
                                @can('delete', $attachment)
                                    <button
                                        @click="confirmDelete({{ json_encode(['id' => $attachment->id, 'original_name' => $attachment->original_name]) }})"
                                        class="p-1.5 bg-white dark:bg-gray-800 rounded-full text-gray-600 dark:text-gray-300 hover:text-red-600 shadow-sm"
                                        title="{{ __('Delete') }}">
                                        <x-fas-trash class="h-4 w-4" />
                                    </button>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    <x-fas-paperclip class="h-12 w-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" />
                    <p class="text-lg font-medium">{{ __('No attachments found') }}</p>
                    <p class="text-sm mt-1">{{ __('Upload your first file to get started') }}</p>
                </div>
            @endif

            <!-- Pagination -->
            @if ($attachments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $attachments->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak>
            <x-confirm-modal x-model="deleteModal" title="{{ __('Delete Attachment') }}" :message="__('Are you sure you want to delete :file? This action cannot be undone.', [
                'file' => '<span class=\'font-medium\' x-text=\'attachmentNameToDelete\'></span>',
            ])"
                confirm-text="{{ __('Delete') }}" cancel-text="{{ __('Cancel') }}"
                @confirm="$refs.deleteForm.submit()">
            </x-confirm-modal>
            <form x-ref="deleteForm" method="POST"
                :action="'{{ route('attachments.index') }}/' + attachmentIdToDelete" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <!-- Preview Modal (for images) -->
        <div x-cloak x-show="previewModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="previewModal = false">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black bg-opacity-90"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full overflow-hidden"
                    @click.stop>
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                x-text="previewAttachment?.original_name"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="previewAttachment?.uploader"></span> •
                                <span x-text="previewAttachment?.created_at"></span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            @can(\App\Enums\PermissionEnum::ATTACHMENTS_VIEW->value)
                                <template x-if="previewAttachment?.id">
                                    <a :href="`/attachments/${previewAttachment.id}/download`"
                                        class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                        title="{{ __('Download') }}">
                                        <x-fas-download class="h-5 w-5" />
                                    </a>
                                </template>
                            @endcan
                            <button @click="previewModal = false"
                                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <x-fas-times class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                    <!-- Image -->
                    <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4 min-h-[300px]">
                        <img x-show="previewAttachment?.id" :src="`/attachments/${previewAttachment.id}/preview`"
                            class="max-h-[70vh] max-w-full object-contain rounded shadow-lg" loading="lazy">
                    </div>
                    <!-- Description -->
                    <div x-show="previewAttachment?.description"
                        class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300" x-text="previewAttachment?.description"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
