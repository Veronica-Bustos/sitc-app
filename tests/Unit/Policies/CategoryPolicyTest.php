<?php

use App\Enums\PermissionEnum;
use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('allows users with categories.view to view categories', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::CATEGORIES_VIEW->value]);
    $user->givePermissionTo(PermissionEnum::CATEGORIES_VIEW->value);

    expect((new CategoryPolicy)->viewAny($user))->toBeTrue();
    expect((new CategoryPolicy)->view($user, Category::factory()->create()))->toBeTrue();
});

it('denies users without categories.view from viewing categories', function () {
    $user = User::factory()->create();

    expect((new CategoryPolicy)->viewAny($user))->toBeFalse();
    expect((new CategoryPolicy)->view($user, Category::factory()->create()))->toBeFalse();
});

it('allows users with categories.create to create categories', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::CATEGORIES_CREATE->value]);
    $user->givePermissionTo(PermissionEnum::CATEGORIES_CREATE->value);

    expect((new CategoryPolicy)->create($user))->toBeTrue();
});

it('allows users with categories.edit to update categories', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::CATEGORIES_EDIT->value]);
    $user->givePermissionTo(PermissionEnum::CATEGORIES_EDIT->value);

    expect((new CategoryPolicy)->update($user, Category::factory()->create()))->toBeTrue();
});

it('allows users with categories.delete to delete/restore/forceDelete categories', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::CATEGORIES_DELETE->value]);
    $user->givePermissionTo(PermissionEnum::CATEGORIES_DELETE->value);
    $category = Category::factory()->create();

    expect((new CategoryPolicy)->delete($user, $category))->toBeTrue();
    expect((new CategoryPolicy)->restore($user, $category))->toBeTrue();
    expect((new CategoryPolicy)->forceDelete($user, $category))->toBeTrue();
});
