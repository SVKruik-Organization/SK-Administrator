<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPosition;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileModule extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileModuleFactory> */
    use HasFactory;

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
     * Get the user profile that owns the module.
     *
     * @return BelongsTo<UserProfile, UserProfileModule>
     */
    public function userProfile(): BelongsTo
    {
        /** @var BelongsTo<UserProfile, UserProfileModule> */
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Get the module that owns the user profile module.
     *
     * @return BelongsTo<Module, UserProfileModule>
     */
    public function module(): BelongsTo
    {
        /** @var BelongsTo<Module, UserProfileModule> */
        return $this->belongsTo(Module::class);
    }
}
