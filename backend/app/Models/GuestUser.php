<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
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
        'last_login_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the guest user's notifications.
     *
     * @return MorphMany<UserNotification, GuestUser>
     */
    public function notifications(): MorphMany
    {
        /** @var MorphMany<UserNotification, GuestUser> */
        return $this->morphMany(UserNotification::class, 'object');
    }

    /**
     * Get the guest's role.
     *
     * @return BelongsTo<UserRole, GuestUser>
     */
    public function role(): BelongsTo
    {
        /** @var BelongsTo<UserRole, GuestUser> */
        return $this->belongsTo(UserRole::class);
    }

    /**
     * Get the owner of the guest. The owner is a user who created the guest.
     *
     * @return BelongsTo<User, GuestUser>
     */
    public function owner(): BelongsTo
    {
        /** @var BelongsTo<User, GuestUser> */
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Get the user profiles for the guest.
     *
     * @return MorphMany<UserProfile, GuestUser>
     */
    public function userProfiles(): MorphMany
    {
        /** @var MorphMany<UserProfile, GuestUser> */
        return $this->morphMany(UserProfile::class, 'object');
    }

    /**
     * Get the event logs for the guest user.
     *
     * @return MorphMany<EventLog, GuestUser>
     */
    public function eventLogs(): MorphMany
    {
        /** @var MorphMany<EventLog, GuestUser> */
        return $this->morphMany(EventLog::class, 'object');
    }
}
