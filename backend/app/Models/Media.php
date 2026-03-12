<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read string $url
 */
class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'disk',
        'size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the object that owns the media.
     *
     * @return MorphTo<Model, Media>
     */
    public function object(): MorphTo
    {
        /** @var MorphTo<Model, Media> */
        return $this->morphTo();
    }

    /**
     * Get the URL of the media.
     */
    public function url(): string
    {
        return Storage::url($this->disk . '/' . $this->name);
    }
}
