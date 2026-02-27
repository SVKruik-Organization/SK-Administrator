<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait HasObjectMorph
{
    /**
     * Get the user or guest that owns this model.
     *
     * @return MorphTo<User|GuestUser, \Illuminate\Database\Eloquent\Model>
     */
    public function object(): MorphTo
    {
        /** @var MorphTo<User|GuestUser, \Illuminate\Database\Eloquent\Model> */
        return $this->morphTo();
    }

    /**
     * Get the type of the object that owns this model.
     *
     * @return class-string<User|GuestUser>
     */
    public function objectType(): string
    {
        /** @var class-string<User|GuestUser> */
        return $this->object_type;
    }

    /**
     * Get the ID of the object that owns this model.
     */
    public function objectId(): string
    {
        /** @var string */
        return $this->object_id;
    }

    /**
     * Check if the object that owns this model is a user.
     */
    public function isForUser(): bool
    {
        return $this->object_type === User::class;
    }

    /**
     * Check if the object that owns this model is a guest user.
     */
    public function isForGuestUser(): bool
    {
        return $this->object_type === GuestUser::class;
    }
}
