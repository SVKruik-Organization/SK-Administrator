<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PanelController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('panel/Index');
    }
}
