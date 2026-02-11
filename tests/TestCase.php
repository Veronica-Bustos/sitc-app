<?php

namespace Tests;

use App\Enums\PermissionEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;

abstract class TestCase extends BaseTestCase
{
    protected function actingAsUserWithPermissions(array $permissions): User
    {
        $user = User::factory()->create();
        $permissionNames = array_map(function ($permission) {
            return $permission instanceof PermissionEnum ? $permission->value : $permission;
        }, $permissions);

        foreach ($permissionNames as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $user->givePermissionTo($permissionNames);

        $this->actingAs($user);

        return $user;
    }
}
