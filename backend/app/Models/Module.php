<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
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
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'array',
    ];

    /**
     * Get the module items for the module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItem, \App\Models\Module>
     */
    public function moduleItems(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItem, \App\Models\Module> */
        return $this->hasMany(ModuleItem::class);
    }

    /**
     * Get the user profile modules for the module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\UserProfileModule, \App\Models\Module>
     */
    public function userProfileModules(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\UserProfileModule, \App\Models\Module> */
        return $this->hasMany(UserProfileModule::class);
    }
}
