<x-layouts.app>
    <div x-data="{
        files: [],
        dragOver: false,
        selectedType: '{{ $preselectedType ?? '' }}',
        selectedId: '{{ $preselectedId ?? '' }}',
        isUploading: false,
    
        handleDrop(event) {
            this.dragOver = false;
            const droppedFiles = Array.from(event.dataTransfer.files);
            this.addFiles(droppedFiles);
        },
    
        handleFileSelect(event) {
            const selectedFiles = Array.from(event.target.files);
            this.addFiles(selectedFiles);
        },
    
        addFiles(newFiles) {
            newFiles.forEach(file => {
                // Check file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    alert('{{ __('File :name is too large. Maximum size is 10MB.', ['name' => '']) }}'.replace(':name', file.name));
                    return;
                }
    
                this.files.push({
                    file: file,
                    name: file.name,
                    size: this.formatFileSize(file.size),
                    type: file.type,
                    isImage: file.type.startsWith('image/'),
                    preview: null
                });
    
                // Generate preview for images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const fileIndex = this.files.findIndex(f => f.file === file);
                        if (fileIndex !== -1) {
                            this.files[fileIndex].preview = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
    
        removeFile(index) {
            this.files.splice(index, 1);
        },
    
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
    
        getIconClass(mimeType) {
            if (mimeType.startsWith('image/')) return 'fa-image text-purple-500';
            if (mimeType === 'application/pdf') return 'fa-file-pdf text-red-500';
            if (mimeType.includes('word')) return 'fa-file-word text-blue-500';
            if (mimeType.includes('excel')) return 'fa-file-excel text-green-500';
            if (mimeType.startsWith('video/')) return 'fa-video text-orange-500';
            if (mimeType.startsWith('audio/')) return 'fa-music text-pink-500';
            return 'fa-file text-gray-500';
        }
    }" @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
        @drop.prevent="handleDrop($event)">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('attachments.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center mb-2">
                <x-fas-arrow-left class="h-4 w-4 mr-1" />
                {{ __('Back to Attachments') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Upload Files') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Upload multiple files to attach to items, movements, or maintenance records') }}</p>
        </div>

        <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data"
            @submit.prevent="if (files.length > 0) { isUploading = true; $el.submit(); }">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - File Upload -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Drop Zone -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Select Files') }}
                        </h3>

                        <!-- Drop Area -->
                        <div :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/20': dragOver, 'border-gray-300 dark:border-gray-600':
                                !dragOver }"
                            class="border-2 border-dashed rounded-lg p-8 text-center transition-colors cursor-pointer hover:border-gray-400 dark:hover:border-gray-500"
                            @click="$refs.fileInput.click()">

                            <input type="file" name="files[]" multiple
                                accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/*"
                                class="hidden" x-ref="fileInput" @change="handleFileSelect($event)">

                            <x-fas-cloud-upload-alt class="h-12 w-12 mx-auto mb-4 text-gray-400 dark:text-gray-500" />
                            <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">
                                {{ __('Drag and drop files here') }}
                            </p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                {{ __('or click to browse') }}
                            </p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">
                                {{ __('Maximum file size: 10MB. Supported: Images, PDF, Word, Excel, Text') }}
                            </p>
                        </div>

                        <!-- File List -->
                        <div x-show="files.length > 0" x-transition class="mt-6 space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span x-text="files.length"></span> {{ __('files selected') }}
                            </h4>

                            <template x-for="(file, index) in files" :key="index">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <!-- Preview or Icon -->
                                    <div
                                        class="w-12 h-12 flex-shrink-0 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center">
                                        <template x-if="file.isImage && file.preview">
                                            <img :src="file.preview" class="w-full h-full object-cover"
                                                alt="">
                                        </template>
                                        <template x-if="!file.isImage || !file.preview">
                                            <i class="fas" :class="getIconClass(file.type)" class="text-xl"></i>
                                        </template>
                                    </div>

                                    <!-- File Info -->
                                    <div class="flex-1 min-w-0">
                                        <p x-text="file.name"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"></p>
                                        <p x-text="file.size" class="text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" @click="removeFile(index)"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 p-1">
                                        <x-fas-times class="h-5 w-5" />
                                    </button>
                                </div>
                            </template>
                        </div>

                        @error('files')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('files.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Metadata -->
                <div class="space-y-6">
                    <!-- Related Entity -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Related To') }}
                        </h3>

                        <div class="space-y-4">
                            <!-- Entity Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Entity Type') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="attachable_type" x-model="selectedType" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('attachable_type') border-red-500 @enderror">
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="item">{{ __('Item') }}</option>
                                    <option value="movement">{{ __('Movement') }}</option>
                                    <option value="maintenance">{{ __('Maintenance Record') }}</option>
                                </select>
                                @error('attachable_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Entity Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Select Entity') }} <span class="text-red-500">*</span>
                                </label>

                                <!-- Items -->
                                <div x-show="selectedType === 'item'" x-transition>
                                    <select name="attachable_id" x-model="selectedId"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <option value="">{{ __('Select Item') }}</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $preselectedId == $item->id && $preselectedType == 'item' ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Movements -->
                                <div x-show="selectedType === 'movement'" x-transition x-cloak>
                                    <select name="attachable_id" x-model="selectedId"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <option value="">{{ __('Select Movement') }}</option>
                                        @foreach ($movements as $movement)
                                            <option value="{{ $movement->id }}"
                                                {{ $preselectedId == $movement->id && $preselectedType == 'movement' ? 'selected' : '' }}>
                                                {{ $movement->item?->name }} - {{ $movement->movement_type }}
                                                ({{ $movement->performed_at?->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Maintenance -->
                                <div x-show="selectedType === 'maintenance'" x-transition x-cloak>
                                    <select name="attachable_id" x-model="selectedId"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <option value="">{{ __('Select Maintenance') }}</option>
                                        @foreach ($maintenanceRecords as $record)
                                            <option value="{{ $record->id }}"
                                                {{ $preselectedId == $record->id && $preselectedType == 'maintenance' ? 'selected' : '' }}>
                                                {{ $record->item?->name }} - {{ $record->type }}
                                                ({{ $record->request_date?->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('attachable_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Options') }}</h3>

                        <div class="space-y-4">
                            <x-forms.checkbox name="is_featured" label="{{ __('Mark as Featured') }}"
                                description="{{ __('Highlight this file as the main image for the entity') }}" />

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Display Order') }}
                                </label>
                                <input type="number" name="order" value="0" min="0"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Lower numbers appear first') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Description') }}
                                </label>
                                <textarea name="description" rows="3"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Optional description for these files...') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('attachments.index') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-center transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            :disabled="files.length === 0 || !selectedType || !selectedId || isUploading"
                            :class="{ 'opacity-50 cursor-not-allowed': files.length === 0 || !selectedType || !selectedId ||
                                    isUploading, 'hover:bg-blue-700': files.length > 0 && selectedType && selectedId &&
                                    !isUploading }"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg transition-colors flex items-center justify-center">
                            <span x-show="!isUploading">{{ __('Upload Files') }}</span>
                            <span x-show="isUploading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                {{ __('Uploading...') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
