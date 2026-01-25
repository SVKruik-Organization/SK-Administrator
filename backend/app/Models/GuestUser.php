<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GuestUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\GuestUserFactory> */
    use HasFactory;

    use HasTimestamps;
    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'date_last_login',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_last_login' => 'datetime',
    ];

    /**
     * Get the guest user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserNotification, \App\Models\GuestUser>
     */
    public function notifications(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserNotification, \App\Models\GuestUser> */
        return $this->morphMany(UserNotification::class, 'object');
    }

    /**
     * Get the guest's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\GuestUser>
     */
    public function role(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\GuestUser> */
        return $this->belongsTo(UserRole::class);
    }

    /**
     * Get the owner of the guest. The owner is a user who created the guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\GuestUser>
     */
    public function owner(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\GuestUser> */
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Get the user profiles for the guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserProfile, \App\Models\GuestUser>
     */
    public function userProfiles(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserProfile, \App\Models\GuestUser> */
        return $this->morphMany(UserProfile::class, 'object');
    }

    /**
     * Get the sessions for the guest user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Session, \App\Models\GuestUser>
     */
    public function sessions(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Session, \App\Models\GuestUser> */
        return $this->morphMany(\App\Models\Session::class, 'object');
    }

    /**
     * Get the event logs for the guest user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\EventLog, \App\Models\GuestUser>
     */
    public function eventLogs(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\EventLog, \App\Models\GuestUser> */
        return $this->morphMany(\App\Models\EventLog::class, 'object');
    }
}
