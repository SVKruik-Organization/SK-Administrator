<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LandingController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Landing');
    }
}
