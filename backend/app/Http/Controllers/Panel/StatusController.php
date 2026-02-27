<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class StatusController extends Controller
{
    public function hardware(): InertiaResponse
    {
        return Inertia::render('panel/status/Hardware');
    }

    public function sites(): InertiaResponse
    {
        return Inertia::render('panel/status/Sites');
    }
}
