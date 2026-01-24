<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomModule extends Model
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
     * Get the user profile associated with the custom module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserProfile, \App\Models\CustomModule>
     */
    public function userProfile(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\UserProfile, \App\Models\CustomModule> */
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Get the custom module items for the custom module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModuleItem, \App\Models\CustomModule>
     */
    public function customModuleItems(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\CustomModuleItem, \App\Models\CustomModule> */
        return $this->hasMany(CustomModuleItem::class);
    }
}
