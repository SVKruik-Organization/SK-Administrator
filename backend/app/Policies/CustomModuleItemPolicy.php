<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CustomModuleItem;
use App\Models\User;
use App\Models\GuestUser;

class CustomModuleItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(GuestUser|User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(GuestUser|User $user, CustomModuleItem $customModuleItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(GuestUser|User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(GuestUser|User $user, CustomModuleItem $customModuleItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(GuestUser|User $user, CustomModuleItem $customModuleItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(GuestUser|User $user, CustomModuleItem $customModuleItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(GuestUser|User $user, CustomModuleItem $customModuleItem): bool
    {
        return false;
    }
}
