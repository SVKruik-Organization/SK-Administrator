<?php

declare(strict_types=1);

namespace App\Http\Middleware\Authentication;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('user')->check()) {
            return redirect()->route('authentication.login');
        }

        return $next($request);
    }
}
