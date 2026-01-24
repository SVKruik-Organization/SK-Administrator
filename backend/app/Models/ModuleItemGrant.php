<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleItemGrant extends Model
{
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'can_create' => 'boolean',
            'can_read' => 'boolean',
            'can_update' => 'boolean',
            'can_delete' => 'boolean',
        ];
    }

    /**
     * Get the module item that owns the grant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ModuleItem, \App\Models\ModuleItemGrant>
     */
    public function moduleItem(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ModuleItem, \App\Models\ModuleItemGrant> */
        return $this->belongsTo(ModuleItem::class);
    }

    /**
     * Get the role that owns the grant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\ModuleItemGrant>
     */
    public function role(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserRole, \App\Models\ModuleItemGrant> */
        return $this->belongsTo(UserRole::class);
    }
}
