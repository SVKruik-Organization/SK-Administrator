<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PromptType;
use App\Traits\HasObjectMorph;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
    /** @use HasFactory<\Database\Factories\UserNotificationFactory> */
    use HasFactory;

    use HasObjectMorph;
    use HasTimestamps;
    use HasUuids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'data',
        'source',
        'url',
        'is_silent',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'is_silent' => 'boolean',
        'type' => PromptType::class,
        'expires_at' => 'datetime',
    ];
}
