<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasObjectMorph;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    /** @use HasFactory<\Database\Factories\EventLogFactory> */
    use HasFactory;

    use HasObjectMorph;
    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'method',
        'ip_address',
    ];
}
