<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserProfile extends Model
{
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'object_type',
        'object_id',
        'name',
        'description',
        'position',
        'language',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Get the parent object (User or Guest) that owns this profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\App\Models\User|\App\Models\Guest, \App\Models\UserProfile>
     */
    public function object(): MorphTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphTo<\App\Models\User|\App\Models\Guest, \App\Models\UserProfile> */
        return $this->morphTo();
    }

    /**
     * Get the custom modules for the user profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModule, \App\Models\UserProfile>
     */
    public function customModules(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModule, \App\Models\UserProfile> */
        return $this->hasMany(CustomModule::class);
    }

    /**
     * Get the user profile modules for the user profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\UserProfileModule, \App\Models\UserProfile>
     */
    public function userProfileModules(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\UserProfileModule, \App\Models\UserProfile> */
        return $this->hasMany(UserProfileModule::class);
    }
}
