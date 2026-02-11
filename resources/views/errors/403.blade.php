<x-layouts.app>
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="max-w-lg text-center">
            <div class="mx-auto mb-4 h-12 w-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                <x-fas-ban class="h-6 w-6" />
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Access denied') }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('You do not have permission to access this page.') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <x-fas-home class="h-4 w-4 mr-2" />
                    {{ __('Go to home') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
