<?php

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class)->group('settings');

it('forbids access without manage permission', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('settings.users.index'))
        ->assertForbidden();
});

it('allows access for users with manage permission', function () {
    $this->seed(RoleSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    actingAs($admin)
        ->get(route('settings.users.index'))
        ->assertOk()
        ->assertViewIs('settings.users')
        ->assertViewHasAll(['users', 'roles', 'roleOptions', 'permissionModules']);
});

it('updates user roles through the management screen', function () {
    $this->seed(RoleSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $target = User::factory()->create();

    actingAs($admin)
        ->from(route('settings.users.index'))
        ->put(route('settings.users.role.update', $target), [
            'role' => RoleEnum::ALMACENISTA->value,
        ])
        ->assertRedirect(route('settings.users.index'));

    expect($target->fresh()->hasRole(RoleEnum::ALMACENISTA->value))->toBeTrue();
});

it('updates role permissions from the management screen', function () {
    $this->seed(RoleSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $role = Role::where('name', RoleEnum::ALMACENISTA->value)->firstOrFail();
    $permissions = [
        PermissionEnum::DASHBOARD_VIEW->value,
        PermissionEnum::ITEMS_VIEW->value,
    ];

    actingAs($admin)
        ->from(route('settings.users.index'))
        ->put(route('settings.roles.permissions.update', $role), [
            'permissions' => $permissions,
        ])
        ->assertRedirect(route('settings.users.index'));

    $role->refresh();

    expect($role->hasPermissionTo(PermissionEnum::DASHBOARD_VIEW->value))->toBeTrue();
    expect($role->hasPermissionTo(PermissionEnum::ITEMS_VIEW->value))->toBeTrue();
});
