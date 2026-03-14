<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\UserNotification;
use App\Models\User;
use App\Models\GuestUser;

class UserNotificationPolicy
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
    public function view(GuestUser|User $user, UserNotification $userNotification): bool
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
    public function update(GuestUser|User $user, UserNotification $userNotification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(GuestUser|User $user, UserNotification $userNotification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(GuestUser|User $user, UserNotification $userNotification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(GuestUser|User $user, UserNotification $userNotification): bool
    {
        return false;
    }
}
