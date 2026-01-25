<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoleFactory> */
    use HasFactory;

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
        'icon',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\GuestUser, \App\Models\UserRole>
     */
    public function guests(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\GuestUser, \App\Models\UserRole> */
        return $this->hasMany(GuestUser::class);
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
