<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserPreferencesController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('user/Preferences');
    }
}
