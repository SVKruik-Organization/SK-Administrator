<?php

namespace App\Models;

use App\Enums\ScheduledTaskStatus;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
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
        'data',
        'status',
        'scheduled_at',
        'tries',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'scheduled_at' => 'datetime',
        'tries' => 'integer',
        'status' => ScheduledTaskStatus::class,
    ];
}
