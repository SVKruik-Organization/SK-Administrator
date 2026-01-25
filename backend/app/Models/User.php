<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserNotification, \App\Models\User>
     */
    public function notifications(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserNotification, \App\Models\User> */
        return $this->morphMany(UserNotification::class, 'object');
    }

    /**
     * Get the user's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\User>
     */
    public function role(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\User> */
        return $this->belongsTo(UserRole::class);
    }

    /**
     * Get the guest users owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\GuestUser, \App\Models\User>
     */
    public function guestUsers(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\GuestUser, \App\Models\User> */
        return $this->hasMany(GuestUser::class, 'owner_id', 'id');
    }

    /**
     * Get the user profiles for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserProfile, \App\Models\User>
     */
    public function userProfiles(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\UserProfile, \App\Models\User> */
        return $this->morphMany(UserProfile::class, 'object');
    }

    /**
     * Get the sessions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Session, \App\Models\User>
     */
    public function sessions(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Session, \App\Models\User> */
        return $this->morphMany(\App\Models\Session::class, 'object');
    }

    /**
     * Get the event logs for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\EventLog, \App\Models\User>
     */
    public function eventLogs(): MorphMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\EventLog, \App\Models\User> */
        return $this->morphMany(\App\Models\EventLog::class, 'object');
    }
}
