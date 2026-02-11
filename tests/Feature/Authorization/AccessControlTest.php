<?php

use App\Enums\PermissionEnum;
use App\Models\Attachment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

function grantPermission(User $user, PermissionEnum $permission): void
{
    $permissionModel = Permission::findOrCreate($permission->value);

    $user->givePermissionTo($permissionModel);
}

it('forbids items index without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('items.index'))
        ->assertForbidden();
});

it('allows items index with permission', function () {
    $user = User::factory()->create();
    grantPermission($user, PermissionEnum::ITEMS_VIEW);

    $this->actingAs($user)
        ->get(route('items.index'))
        ->assertSuccessful();
});

it('forbids item history without permission', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $this->actingAs($user)
        ->get(route('items.history', $item))
        ->assertForbidden();
});

it('forbids attachments index without permission', function () {
    $user = User::factory()->create();
    Attachment::factory()->create();

    $this->actingAs($user)
        ->get(route('attachments.index'))
        ->assertForbidden();
});
