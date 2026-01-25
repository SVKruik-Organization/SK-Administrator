<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomModuleItem extends Model
{
    /** @use HasFactory<\Database\Factories\CustomModuleItemFactory> */
    use HasFactory;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\CustomModule, \App\Models\CustomModuleItem>
     */
    public function customModule(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\CustomModule, \App\Models\CustomModuleItem> */
        return $this->belongsTo(CustomModule::class);
    }

    /**
     * Get the module item that owns the custom module item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ModuleItem, \App\Models\CustomModuleItem>
     */
    public function moduleItem(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ModuleItem, \App\Models\CustomModuleItem> */
        return $this->belongsTo(ModuleItem::class);
    }
}
