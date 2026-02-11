<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Reports') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Generate and download inventory reports') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Stock por Ubicación --}}
        @can(\App\Enums\PermissionEnum::REPORTS_STOCK->value)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <x-icon name="fas-warehouse" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h2 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Stock by Location') }}</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('View inventory stock distributed across different locations. Filter by category, status, and condition.') }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        <x-icon name="fas-chart-bar" class="w-4 h-4 inline mr-1" />
                        {{ __('Inventory report') }}
                    </span>
                    <x-button href="{{ route('reports.stock') }}" variant="primary" size="sm">
                        {{ __('View Report') }}
                    </x-button>
                </div>
            </div>
        @endcan

        {{-- Historial de Movimientos --}}
        @can(\App\Enums\PermissionEnum::REPORTS_MOVEMENTS->value)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <x-icon name="fas-exchange-alt" class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <h2 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Movement History') }}</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('Track all inventory movements including check-ins, check-outs, and transfers between locations.') }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        <x-icon name="fas-history" class="w-4 h-4 inline mr-1" />
                        {{ __('Activity report') }}
                    </span>
                    <x-button href="{{ route('reports.movements') }}" variant="primary" size="sm">
                        {{ __('View Report') }}
                    </x-button>
                </div>
            </div>
        @endcan

        {{-- Ítems Fuera de Servicio --}}
        @can(\App\Enums\PermissionEnum::REPORTS_OUT_OF_SERVICE->value)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <x-icon name="fas-tools" class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                    <h2 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Out of Service Items') }}
                    </h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('View items in repair, damaged, lost, or retired. Includes days out of service and estimated costs.') }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        <x-icon name="fas-exclamation-triangle" class="w-4 h-4 inline mr-1" />
                        {{ __('Status report') }}
                    </span>
                    <x-button href="{{ route('reports.out-of-service') }}" variant="primary" size="sm">
                        {{ __('View Report') }}
                    </x-button>
                </div>
            </div>
        @endcan
    </div>

    {{-- Información adicional --}}
    <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
            <x-icon name="fas-info-circle" class="w-5 h-5 inline mr-2" />
            {{ __('About Reports') }}
        </h3>
        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
            <li class="flex items-start">
                <x-icon name="fas-check" class="w-4 h-4 mt-1 mr-2 text-green-500" />
                {{ __('All reports support filters to refine results') }}
            </li>
            <li class="flex items-start">
                <x-icon name="fas-check" class="w-4 h-4 mt-1 mr-2 text-green-500" />
                {{ __('Export any report to Excel format with formatted tables and summaries') }}
            </li>
            <li class="flex items-start">
                <x-icon name="fas-check" class="w-4 h-4 mt-1 mr-2 text-green-500" />
                {{ __('Reports are generated in real-time from current inventory data') }}
            </li>
            <li class="flex items-start">
                <x-icon name="fas-check" class="w-4 h-4 mt-1 mr-2 text-green-500" />
                {{ __('Access to reports is controlled by user permissions') }}
            </li>
        </ul>
    </div>
</x-layouts.app>
