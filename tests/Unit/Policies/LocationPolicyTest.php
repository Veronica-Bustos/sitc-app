<?php

use App\Enums\PermissionEnum;
use App\Models\Location;
use App\Models\User;
use App\Policies\LocationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('allows users with locations.view to view locations', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::LOCATIONS_VIEW->value]);
    $user->givePermissionTo(PermissionEnum::LOCATIONS_VIEW->value);

    expect((new LocationPolicy)->viewAny($user))->toBeTrue();
    expect((new LocationPolicy)->view($user, Location::factory()->create()))->toBeTrue();
});

it('denies users without locations.view from viewing locations', function () {
    $user = User::factory()->create();

    expect((new LocationPolicy)->viewAny($user))->toBeFalse();
    expect((new LocationPolicy)->view($user, Location::factory()->create()))->toBeFalse();
});

it('allows users with locations.create to create locations', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::LOCATIONS_CREATE->value]);
    $user->givePermissionTo(PermissionEnum::LOCATIONS_CREATE->value);

    expect((new LocationPolicy)->create($user))->toBeTrue();
});

it('allows users with locations.edit to update locations', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::LOCATIONS_EDIT->value]);
    $user->givePermissionTo(PermissionEnum::LOCATIONS_EDIT->value);

    expect((new LocationPolicy)->update($user, Location::factory()->create()))->toBeTrue();
});

it('allows users with locations.delete to delete/restore/forceDelete locations', function () {
    $user = User::factory()->create();
    Permission::firstOrCreate(['name' => PermissionEnum::LOCATIONS_DELETE->value]);
    $user->givePermissionTo(PermissionEnum::LOCATIONS_DELETE->value);
    $location = Location::factory()->create();

    expect((new LocationPolicy)->delete($user, $location))->toBeTrue();
    expect((new LocationPolicy)->restore($user, $location))->toBeTrue();
    expect((new LocationPolicy)->forceDelete($user, $location))->toBeTrue();
});
