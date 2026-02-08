@props([
    'title' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-lg',
        'lg' => 'sm:max-w-2xl',
        'xl' => 'sm:max-w-4xl',
        'full' => 'sm:max-w-full sm:m-4',
    ];
@endphp

<div x-data="{ open: false }" x-modelable="open" {{ $attributes }} x-cloak x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
        @click="open = false"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 w-full {{ $sizes[$size] ?? $sizes['md'] }}">

            @if ($title)
                <div
                    class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                        {{ $title }}
                    </h3>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
