<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasTimestamps;
    use HasUuids;
    use Notifiable;

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
            'date_last_login' => 'datetime',
        ];
    }

    /**
     * Get the user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserNotification, \App\Models\User>
     */
    public function notifications(): MorphOne
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserNotification, \App\Models\User> */
        return $this->morphOne(UserNotification::class, 'object');
    }

    /**
     * Get the user's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<\App\Models\UserRole, \App\Models\User>
     */
    public function role(): HasOne
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasOne<\App\Models\UserRole, \App\Models\User> */
        return $this->hasOne(UserRole::class);
    }

    /**
     * Get the guest users owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Guest, \App\Models\User>
     */
    public function guestUsers(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Guest, \App\Models\User> */
        return $this->hasMany(Guest::class, 'owner_id', 'id');
    }

    /**
     * Get the user profile for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserProfile, \App\Models\User>
     */
    public function userProfile(): MorphOne
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\UserProfile, \App\Models\User> */
        return $this->morphOne(UserProfile::class, 'object');
    }
}
