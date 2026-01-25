<?php

namespace App\Models;

use App\Traits\HasObjectMorph;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    /** @use HasFactory<\Database\Factories\EventLogFactory> */
    use HasFactory;

    use HasTimestamps;
    use HasUuids;
    use HasObjectMorph;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'description',
        'endpoint',
        'method',
        'ip_address',
    ];
}
