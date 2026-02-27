<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPanelUrl;
use App\Traits\HasPosition;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomModuleItem extends Model
{
    /** @use HasFactory<\Database\Factories\CustomModuleItemFactory> */
    use HasFactory;

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
        'position',
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
     * Get the custom module that owns the item.
     *
     * @return BelongsTo<CustomModule, CustomModuleItem>
     */
    public function customModule(): BelongsTo
    {
        /** @var BelongsTo<CustomModule, CustomModuleItem> */
        return $this->belongsTo(CustomModule::class);
    }

    /**
     * Get the module item that owns the custom module item.
     *
     * @return BelongsTo<ModuleItem, CustomModuleItem>
     */
    public function moduleItem(): BelongsTo
    {
        /** @var BelongsTo<ModuleItem, CustomModuleItem> */
        return $this->belongsTo(ModuleItem::class);
    }

    /**
     * @return array<int, string>
     */
    protected function panelPathSegments(): array
    {
        $customModule = $this->customModule;
        $moduleItem = $this->moduleItem;

        if ($customModule === null || $moduleItem === null) {
            return [];
        }

        /** @var array<string, string> $moduleName */
        $moduleName = $customModule->name;

        /** @var array<string, string> $itemName */
        $itemName = $moduleItem->name;

        return [$moduleName['en'], $itemName['en']];
    }
}
