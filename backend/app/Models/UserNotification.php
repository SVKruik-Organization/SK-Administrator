<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PromptTypes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserNotification extends Model
{
    use HasTimestamps;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'level',
        'data',
        'source',
        'url',
        'is_silent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'is_silent' => 'boolean',
        'level' => PromptTypes::class,
    ];

    /**
     * Get the user that owns the notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\App\Models\User|\App\Models\Guest, \App\Models\UserNotification>
     */
    public function object(): MorphTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphTo<\App\Models\User|\App\Models\Guest, \App\Models\UserNotification> */
        return $this->morphTo();
    }
}
