<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Guest extends Model
{
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'key',
    ];

    /**
     * Get the guest user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserNotification, \App\Models\Guest>
     */
    public function notifications(): MorphOne
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserNotification, \App\Models\Guest> */
        return $this->morphOne(UserNotification::class, 'object');
    }

    /**
     * Get the guest's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\Guest>
     */
    public function role(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\Guest> */
        return $this->belongsTo(UserRole::class);
    }

    /**
     * Get the owner of the guest. The owner is a user who created the guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Guest>
     */
    public function owner(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Guest> */
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Get the user profile for the guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserProfile, \App\Models\Guest>
     */
    public function userProfile(): MorphOne
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserProfile, \App\Models\Guest> */
        return $this->morphOne(UserProfile::class, 'object');
    }
}
