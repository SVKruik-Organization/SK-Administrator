<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GuestUser;
use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\UserProfileRepository;
use Illuminate\Database\Eloquent\Collection;

class UserProfileService
{
    public function __construct(
        private UserProfileRepository $userProfileRepository
    ) {}

    /**
     * Get all user profiles for a user or guest user.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @return Collection<int, UserProfile>
     */
    public function getUserProfiles(User|GuestUser $user): Collection
    {
        return $this->userProfileRepository->getUserProfiles($user);
    }

    /**
     * Get the last used profile for a user or guest user.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @return UserProfile The last used profile.
     */
    public function getActiveProfile(User|GuestUser $user): UserProfile
    {
        return $this->userProfileRepository->getActiveProfile($user);
    }

    /**
     * Get the language of the active profile for a user or guest user.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @return string The language of the active profile.
     */
    public function getActiveProfileLanguage(User|GuestUser $user): string
    {
        return $this->getActiveProfile($user)->language;
    }

    /**
     * Get the first item URL for a user profile.
     *
     * @param  UserProfile  $userProfile  The user profile.
     * @return string The first item URL.
     */
    public function getFirstItemUrl(UserProfile $userProfile): string
    {
        if ($userProfile->customModules->count() > 0) {
            $firstCustomModule = $userProfile->customModules->first();

            if ($firstCustomModule !== null) {
                return $firstCustomModule->panelUrl();
            }
        }

        if ($userProfile->userProfileModules->count() > 0) {
            $firstModule = $userProfile->userProfileModules->first()?->module;

            if ($firstModule !== null) {
                return $firstModule->panelUrl();
            }
        }

        return '/panel';
    }

    /**
     * Switch to a different user profile.
     *
     * @param  UserProfile  $userProfile  The user profile to switch to.
     * @return UserProfile The switched profile.
     */
    public function switchProfile(UserProfile $userProfile): UserProfile
    {
        return $this->userProfileRepository->switchProfile($userProfile);
    }
}
