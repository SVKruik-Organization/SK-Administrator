<?php

namespace App\Models;

use App\Traits\HasObjectMorph;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    use HasObjectMorph;
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'position',
        'language',
        'last_usage_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'position' => 'integer',
        'last_usage_at' => 'datetime',
    ];

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
