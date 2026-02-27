<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\GuestUser;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends AbstractRepository<UserProfile>
 */
class UserProfileRepository extends AbstractRepository
{
    public function __construct(UserProfile $model)
    {
        parent::__construct($model);
    }

    /**
     * Get a user profile by its ID.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @param  string  $profileId  The ID of the profile to get.
     * @return UserProfile The user profile.
     */
    public function getUserProfileByProfileId(User|GuestUser $user, string $profileId): UserProfile
    {
        return $user->userProfiles()
            ->where('id', $profileId)
            ->firstOrFail();
    }

    /**
     * Get all user profiles for a user or guest user.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @return Collection<int, UserProfile>
     */
    public function getUserProfiles(User|GuestUser $user): Collection
    {
        return $user->userProfiles()
            ->get();
    }

    /**
     * Get the last used profile for a user or guest user.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @return UserProfile The last used profile.
     */
    public function getLastUsedProfile(User|GuestUser $user): UserProfile
    {
        /** @var UserProfile|null $lastUsedProfile */
        $lastUsedProfile = $user->userProfiles()
            ->orderBy('last_usage_at', 'desc')
            ->first();

        if (!$lastUsedProfile) {
            abort(404, 'No last used profile found');
        }

        return $this->update($lastUsedProfile, ['last_usage_at' => now()]);
    }

    /**
     * Switch to a different user profile.
     *
     * @param  User|GuestUser  $user  The user or guest user.
     * @param  UserProfile  $userProfile  The user profile to switch to.
     * @return UserProfile The switched profile.
     */
    public function switchProfile(User|GuestUser $user, UserProfile $userProfile): UserProfile
    {
        return $this->update($userProfile, ['last_usage_at' => now()]);
    }
}
