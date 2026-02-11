<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ITEMS_VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::ITEMS_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_DELETE->value);
    }

    /**
     * Determine whether the user can view the item history.
     */
    public function history(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_HISTORY->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_DELETE->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        return $user->can(PermissionEnum::ITEMS_DELETE->value);
    }
}
