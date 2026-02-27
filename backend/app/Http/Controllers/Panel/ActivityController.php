<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ActivityController extends Controller
{
    public function logs(): InertiaResponse
    {
        return Inertia::render('panel/activity/Logs');
    }

    public function purchases(): InertiaResponse
    {
        return Inertia::render('panel/activity/Purchases');
    }
}
