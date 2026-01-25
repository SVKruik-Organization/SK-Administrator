<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasObjectMorph;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasObjectMorph;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'ip_address',
        'user_agent',
        'payload',
        'app_name',
        'last_activity',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_activity' => 'integer',
        'expires_at' => 'datetime',
    ];
}
