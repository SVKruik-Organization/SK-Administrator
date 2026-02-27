<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class GuildController extends Controller
{
    public function admins(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Admins');
    }

    public function blocked(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Blocked');
    }

    public function economy(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Economy');
    }

    public function events(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Events');
    }

    public function overview(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Overview');
    }

    public function tier(): InertiaResponse
    {
        return Inertia::render('panel/guilds/Tier');
    }
}
