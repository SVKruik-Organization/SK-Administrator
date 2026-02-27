<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasPosition
{
    /**
     * Add a global scope so models using this trait
     * are always ordered by their position column.
     */
    protected static function bootHasPosition(): void
    {
        static::addGlobalScope('position', static function (Builder $builder): void {
            $builder->orderBy('position', 'asc');
        });
    }

    /**
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position', 'asc');
    }
}
