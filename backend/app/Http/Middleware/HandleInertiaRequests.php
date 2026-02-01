<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var \App\Models\User|\App\Models\GuestUser|null $user */
        $user = $request->user('user') ?? $request->user('guest') ?? null;

        if ($user) {
            $userType = $user instanceof User ? User::class : GuestUser::class;
            $user->load('role');
        } else {
            $userType = null;
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
                'user_type' => $userType,
            ],
        ];
    }
}
