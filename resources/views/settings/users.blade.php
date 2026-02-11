<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('settings.profile.edit') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Settings') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Users') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <div
            class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
            <x-fas-shield-halved class="h-3 w-3" />
            {{ __('Access Control') }}
        </div>
        <h1 class="mt-3 text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Users, Roles, and Permissions') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Assign a single role per user and manage permissions at the role level.') }}
        </p>
    </div>

    @if (session('status'))
        <div
            class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-900/30 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="p-6">
        <div class="space-y-6">
            <!-- Users Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-5 bg-gradient-to-r from-slate-50 via-white to-amber-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ __('User Roles') }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Each user keeps exactly one role, and roles grant permissions.') }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <x-filters action="{{ route('settings.users.index') }}" :has-filters="request()->hasAny(['search', 'role'])" :active-filters-count="count(array_filter(request()->only(['role']), fn($value) => $value !== null && $value !== ''))"
                        search-placeholder="{{ __('Search by name or email...') }}"
                        search-value="{{ request('search') }}">
                        <x-select name="role" :options="$roleOptions" :value="request('role')"
                            placeholder="{{ __('All Roles') }}" />
                    </x-filters>

                    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <x-th-sortable name="name">{{ __('Name') }}</x-th-sortable>
                                        <x-th-sortable name="email">{{ __('Email') }}</x-th-sortable>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Role') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($users as $user)
                                        @php
                                            $currentRole = $user->roles->first()?->name;
                                            $roleLabel =
                                                $currentRole && isset($roleOptions[$currentRole])
                                                    ? $roleOptions[$currentRole]
                                                    : $currentRole ?? __('Unassigned');
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    #{{ $user->id }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-100">
                                                    {{ $roleLabel }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form method="POST"
                                                    action="{{ route('settings.users.role.update', $user) }}"
                                                    class="flex flex-col lg:flex-row lg:items-center gap-3 justify-end">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="min-w-[200px]">
                                                        <x-select name="role" :options="$roleOptions" :value="$currentRole"
                                                            placeholder="{{ __('Select role') }}" />
                                                    </div>
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                                                        <x-fas-floppy-disk class="mr-2 h-4 w-4" />
                                                        {{ __('Save') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                {{ __('No users found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($users->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Permissions Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-5 bg-gradient-to-r from-emerald-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900 dark:to-emerald-950/40">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ __('Role Permissions') }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Toggle permissions for each role. Users inherit permissions from their role.') }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-6">
                    @foreach ($roles as $role)
                        @php
                            $roleTitle = isset($roleOptions[$role->name]) ? $roleOptions[$role->name] : $role->name;
                            $permissionCount = $role->permissions->count();
                        @endphp
                        <div
                            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/40 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                        {{ $roleTitle }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Permissions assigned') }}: {{ $permissionCount }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                                    {{ $role->name }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('settings.roles.permissions.update', $role) }}"
                                class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    @foreach ($permissionModules as $module => $permissions)
                                        <div
                                            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                                            <p
                                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                {{ $module }}
                                            </p>
                                            <div class="mt-3 space-y-2">
                                                @foreach ($permissions as $value => $label)
                                                    <label
                                                        class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $value }}" @checked($role->permissions->contains('name', $value))
                                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900">
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-5 flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-700">
                                        <x-fas-check class="mr-2 h-4 w-4" />
                                        {{ __('Update permissions') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
