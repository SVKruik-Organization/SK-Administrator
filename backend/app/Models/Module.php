<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPanelUrl;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasPanelUrl;
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
     * @return HasMany<ModuleItem, Module>
     */
    public function moduleItems(): HasMany
    {
        /** @var HasMany<ModuleItem, Module> */
        return $this->hasMany(ModuleItem::class);
    }

    /**
     * Get the user profile modules for the module.
     *
     * @return HasMany<UserProfileModule, Module>
     */
    public function userProfileModules(): HasMany
    {
        /** @var HasMany<UserProfileModule, Module> */
        return $this->hasMany(UserProfileModule::class);
    }

    /**
     * @return array<int, string>
     */
    protected function panelPathSegments(): array
    {
        /** @var array<string, string> $name */
        $name = $this->name;

        return [$name['en']];
    }
}
