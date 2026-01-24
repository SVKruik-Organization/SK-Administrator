<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the users for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User, \App\Models\UserRole>
     */
    public function users(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User, \App\Models\UserRole> */
        return $this->hasMany(User::class);
    }

    /**
     * Get the guests for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Guest, \App\Models\UserRole>
     */
    public function guests(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Guest, \App\Models\UserRole> */
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the module item grants for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItemGrant, \App\Models\UserRole>
     */
    public function moduleItemGrants(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItemGrant, \App\Models\UserRole> */
        return $this->hasMany(ModuleItemGrant::class);
    }
}
