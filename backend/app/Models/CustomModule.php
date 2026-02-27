<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPanelUrl;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomModule extends Model
{
    /** @use HasFactory<\Database\Factories\CustomModuleFactory> */
    use HasFactory;

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
     * Get the user profile associated with the custom module.
     *
     * @return BelongsTo<UserProfile, CustomModule>
     */
    public function userProfile(): BelongsTo
    {
        /** @var BelongsTo<UserProfile, CustomModule> */
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Get the custom module items for the custom module.
     *
     * @return HasMany<CustomModuleItem, CustomModule>
     */
    public function customModuleItems(): HasMany
    {
        /** @var HasMany<CustomModuleItem, CustomModule> */
        return $this->hasMany(CustomModuleItem::class);
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
