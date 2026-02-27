<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class OperatorController extends Controller
{
    public function members(): InertiaResponse
    {
        return Inertia::render('panel/operators/Members');
    }

    public function owners(): InertiaResponse
    {
        return Inertia::render('panel/operators/Owners');
    }

    public function teams(): InertiaResponse
    {
        return Inertia::render('panel/operators/Teams');
    }
}
