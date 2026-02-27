<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RecordController extends Controller
{
    public function bugReports(): InertiaResponse
    {
        return Inertia::render('panel/records/BugReports');
    }

    public function questions(): InertiaResponse
    {
        return Inertia::render('panel/records/Questions');
    }

    public function tickets(): InertiaResponse
    {
        return Inertia::render('panel/records/Tickets');
    }

    public function userReports(): InertiaResponse
    {
        return Inertia::render('panel/records/UserReports');
    }

    public function userWarnings(): InertiaResponse
    {
        return Inertia::render('panel/records/UserWarnings');
    }
}
