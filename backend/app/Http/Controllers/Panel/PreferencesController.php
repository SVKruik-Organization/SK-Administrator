<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PreferencesController extends Controller
{
    public function notifications(): InertiaResponse
    {
        return Inertia::render('panel/preferences/Notifications');
    }

    public function profile(): InertiaResponse
    {
        return Inertia::render('panel/preferences/Profile');
    }

    public function security(): InertiaResponse
    {
        return Inertia::render('panel/preferences/Security');
    }
}
