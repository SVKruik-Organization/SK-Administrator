<?php

namespace App\Models;

use App\Enums\VerificationReason;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVerification extends Model
{
    /** @use HasFactory<\Database\Factories\UserVerificationFactory> */
    use HasFactory;

    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pin',
        'reason',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pin' => 'integer',
        'reason' => VerificationReason::class,
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the verification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\UserVerification>
     */
    public function user(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\UserVerification> */
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
