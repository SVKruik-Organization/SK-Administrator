<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OverwayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private OverwayService $overwayService;

    public function __construct(OverwayService $overwayService)
    {
        $this->overwayService = $overwayService;
    }

    public function login(): RedirectResponse
    {
        /** @var string */
        $overwayURL = config('overway.url');
        if (!$overwayURL) {
            throw new \Exception('Overway URL is not set');
        }

        return redirect()->away($overwayURL.'/login/administrator');
    }

    public function callback(Request $request): RedirectResponse
    {
        // Fetch the user from the Auth API
        $token = $request->query('token') ?? throw new \Exception('Token is required');
        $user = $this->overwayService->fetchUserFromAuthAPI($token);

        Auth::logout();

        if ($user instanceof User) {
            Auth::guard('user')->login($user);
        } else {
            Auth::guard('guest')->login($user);
        }

        return redirect()->route('panel.index');
    }
}
