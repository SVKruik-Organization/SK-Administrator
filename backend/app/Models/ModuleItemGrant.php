<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleItemGrant extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleItemGrantFactory> */
    use HasFactory;

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
     * @return BelongsTo<ModuleItem, ModuleItemGrant>
     */
    public function moduleItem(): BelongsTo
    {
        /** @var BelongsTo<ModuleItem, ModuleItemGrant> */
        return $this->belongsTo(ModuleItem::class);
    }

    /**
     * Get the role that owns the grant.
     *
     * @return BelongsTo<UserRole, ModuleItemGrant>
     */
    public function role(): BelongsTo
    {
        /** @var BelongsTo<UserRole, ModuleItemGrant> */
        return $this->belongsTo(UserRole::class);
    }
}
