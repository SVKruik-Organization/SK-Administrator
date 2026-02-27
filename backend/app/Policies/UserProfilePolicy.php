<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\GuestUser;
use App\Models\User;
use App\Models\UserProfile;

class UserProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|GuestUser $user): bool
    {
        return $user->userProfiles()->count() > 0;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|GuestUser $user, UserProfile $userProfile): bool
    {
        return $userProfile->object()->is($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|GuestUser $user, UserProfile $userProfile): bool
    {
        return $userProfile->object()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|GuestUser $user, UserProfile $userProfile): bool
    {
        return $userProfile->object()->is($user);
    }
}
