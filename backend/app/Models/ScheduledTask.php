<?php

namespace App\Models;

use App\Enums\ScheduledTaskStatus;
use App\Enums\ScheduledTaskType;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduledTask extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduledTaskFactory> */
    use HasFactory;

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
        'type' => ScheduledTaskType::class,
    ];
}
