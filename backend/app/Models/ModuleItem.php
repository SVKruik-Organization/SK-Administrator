<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPanelUrl;
use App\Traits\HasPosition;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleItem extends Model
{
    use HasPanelUrl;
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    /**
     * Get the custom module items for the module item.
     *
     * @return HasMany<CustomModuleItem, ModuleItem>
     */
    public function customModuleItems(): HasMany
    {
        /** @var HasMany<CustomModuleItem, ModuleItem> */
        return $this->hasMany(CustomModuleItem::class);
    }

    /**
     * Get the module item grants for the module item.
     *
     * @return HasMany<ModuleItemGrant, ModuleItem>
     */
    public function grants(): HasMany
    {
        /** @var HasMany<ModuleItemGrant, ModuleItem> */
        return $this->hasMany(ModuleItemGrant::class);
    }

    /**
     * Get the module that owns the module item.
     *
     * @return BelongsTo<Module, ModuleItem>
     */
    public function module(): BelongsTo
    {
        /** @var BelongsTo<Module, ModuleItem> */
        return $this->belongsTo(Module::class);
    }

    /**
     * @return array<int, string>
     */
    protected function panelPathSegments(): array
    {
        $module = $this->module;

        /** @var array<string, string> $name */
        $name = $this->name;

        if ($module === null) {
            return [$name['en']];
        }

        /** @var array<string, string> $moduleName */
        $moduleName = $module->name;

        return [$moduleName['en'], $name['en']];
    }
}
