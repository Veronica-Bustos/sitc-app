<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
    class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon="fas-gauge" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-layouts.sidebar-link>

                <!-- Inventario -->
                <x-layouts.sidebar-two-level-link-parent :title="__('Inventory')" icon="fas-boxes-stacked" :active="request()->routeIs('items.*') || request()->routeIs('categories.*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('items.index') }}" icon='fas-box' :active="request()->routeIs('items.*')">
                        {{ __('Items') }}
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('categories.index') }}" icon='fas-tags'
                        :active="request()->routeIs('categories.*')">
                        {{ __('Categories') }}
                    </x-layouts.sidebar-two-level-link>
                </x-layouts.sidebar-two-level-link-parent>

                <!-- Logística -->
                <x-layouts.sidebar-two-level-link-parent :title="__('Logistics')" icon="fas-truck" :active="request()->routeIs('locations.*') || request()->routeIs('inventory-movements.*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('locations.index') }}" icon='fas-location-dot'
                        :active="request()->routeIs('locations.*')">
                        {{ __('Locations') }}
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('inventory-movements.index') }}"
                        icon='fas-arrow-right-arrow-left' :active="request()->routeIs('inventory-movements.*')">
                        {{ __('Movements') }}
                    </x-layouts.sidebar-two-level-link>
                </x-layouts.sidebar-two-level-link-parent>

                <!-- Mantenimiento -->
                <x-layouts.sidebar-link href="{{ route('maintenance-records.index') }}" icon="fas-wrench"
                    :active="request()->routeIs('maintenance-records.*')">
                    {{ __('Maintenance') }}
                </x-layouts.sidebar-link>

                <!-- Medios -->
                <x-layouts.sidebar-link href="{{ route('attachments.index') }}" icon="fas-paperclip"
                    :active="request()->routeIs('attachments.*')">
                    {{ __('Media') }}
                </x-layouts.sidebar-link>

                <!-- Configuración -->
                <x-layouts.sidebar-two-level-link-parent :title="__('Settings')" icon="fas-gear" :active="request()->routeIs('settings.*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.profile.edit') }}" icon='fas-user'
                        :active="request()->routeIs('settings.profile.*')">
                        {{ __('Profile') }}
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.password.edit') }}" icon='fas-lock'
                        :active="request()->routeIs('settings.password.*')">
                        {{ __('Password') }}
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('settings.appearance.edit') }}" icon='fas-palette'
                        :active="request()->routeIs('settings.appearance.*')">
                        {{ __('Appearance') }}
                    </x-layouts.sidebar-two-level-link>
                    @can('manage', App\Models\User::class)
                        <x-layouts.sidebar-two-level-link href="{{ route('settings.users.index') }}" icon='fas-users'
                            :active="request()->routeIs('settings.users.*')">
                            {{ __('Users') }}
                        </x-layouts.sidebar-two-level-link>
                    @endcan
                </x-layouts.sidebar-two-level-link-parent>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div x-show="sidebarOpen" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="border-t border-gray-200 dark:border-gray-700 p-4 text-xs text-gray-500 dark:text-gray-400">
            <p class="text-center">{{ config('app.name') }} v1.0</p>
            <p class="text-center mt-1">&copy; {{ date('Y') }}</p>
        </div>
    </div>
</aside>
