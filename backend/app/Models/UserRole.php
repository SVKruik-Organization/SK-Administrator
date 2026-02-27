<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPosition;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoleFactory> */
    use HasFactory;

    use HasPosition;
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
     * @return HasMany<User, UserRole>
     */
    public function users(): HasMany
    {
        /** @var HasMany<User, UserRole> */
        return $this->hasMany(User::class);
    }

    /**
     * Get the guests for the role.
     *
     * @return HasMany<GuestUser, UserRole>
     */
    public function guests(): HasMany
    {
        /** @var HasMany<GuestUser, UserRole> */
        return $this->hasMany(GuestUser::class);
    }

    /**
     * Get the module item grants for the role.
     *
     * @return HasMany<ModuleItemGrant, UserRole>
     */
    public function moduleItemGrants(): HasMany
    {
        /** @var HasMany<ModuleItemGrant, UserRole> */
        return $this->hasMany(ModuleItemGrant::class);
    }
}
