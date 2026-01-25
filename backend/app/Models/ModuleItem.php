<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleItem extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModuleItem, \App\Models\ModuleItem>
     */
    public function customModuleItems(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModuleItem, \App\Models\ModuleItem> */
        return $this->hasMany(CustomModuleItem::class);
    }

    /**
     * Get the module item grants for the module item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItemGrant, \App\Models\ModuleItem>
     */
    public function grants(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ModuleItemGrant, \App\Models\ModuleItem> */
        return $this->hasMany(ModuleItemGrant::class);
    }

    /**
     * Get the module that owns the module item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Module, \App\Models\ModuleItem>
     */
    public function module(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Module, \App\Models\ModuleItem> */
        return $this->belongsTo(Module::class);
    }
}
