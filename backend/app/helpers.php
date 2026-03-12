<?php

declare(strict_types=1);

use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('get_user')) {
    /**
     * Get the current logged in user.
     */
    function get_user(): User|GuestUser|null
    {
        return Auth::guard('user')->user() ?? Auth::guard('guest')->user() ?? null;
    }
}
