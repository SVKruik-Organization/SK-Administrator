<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the user's notifications.
     *
     * @return MorphMany<UserNotification, User>
     */
    public function notifications(): MorphMany
    {
        /** @var MorphMany<UserNotification, User> */
        return $this->morphMany(UserNotification::class, 'object');
    }

    /**
     * Get the user's role.
     *
     * @return BelongsTo<UserRole, User>
     */
    public function role(): BelongsTo
    {
        /** @var BelongsTo<UserRole, User> */
        return $this->belongsTo(UserRole::class);
    }

    /**
     * Get the guest users owned by the user.
     *
     * @return HasMany<GuestUser, User>
     */
    public function guestUsers(): HasMany
    {
        /** @var HasMany<GuestUser, User> */
        return $this->hasMany(GuestUser::class, 'owner_id', 'id');
    }

    /**
     * Get the user profiles for the user.
     *
     * @return MorphMany<UserProfile, User>
     */
    public function userProfiles(): MorphMany
    {
        /** @var MorphMany<UserProfile, User> */
        return $this->morphMany(UserProfile::class, 'object');
    }

    /**
     * Get the event logs for the user.
     *
     * @return MorphMany<EventLog, User>
     */
    public function eventLogs(): MorphMany
    {
        /** @var MorphMany<EventLog, User> */
        return $this->morphMany(EventLog::class, 'object');
    }
}
