<?php

namespace App\Http\Controllers\Settings;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Filters\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateRolePermissionsRequest;
use App\Http\Requests\Settings\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request, UserFilter $filters): View
    {
        $this->authorize('manage', User::class);

        $users = User::query()
            ->with('roles')
            ->filter($filters)
            ->paginate(20)
            ->withQueryString();

        $roles = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();

        $roleOptions = collect(RoleEnum::cases())
            ->mapWithKeys(fn(RoleEnum $role) => [$role->value => $role->label()]);

        return view('settings.users', [
            'users' => $users,
            'roles' => $roles,
            'roleOptions' => $roleOptions,
            'permissionModules' => PermissionEnum::byModule(),
        ]);
    }

    public function updateUserRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $user->syncRoles([$request->validated()['role']]);

        return back()->with('status', __('User role updated successfully'));
    }

    public function updateRolePermissions(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $permissions = $request->validated()['permissions'] ?? [];

        $role->syncPermissions($permissions);

        return back()->with('status', __('Role permissions updated successfully'));
    }
}
