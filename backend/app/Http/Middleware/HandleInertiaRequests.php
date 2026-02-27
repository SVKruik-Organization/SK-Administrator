<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\ModuleItemResource;
use App\Http\Resources\UserProfileResource;
use App\Models\GuestUser;
use App\Models\ModuleItem;
use App\Models\User;
use App\Services\UserProfileService;
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
        /** @var User|GuestUser|null $user */
        $user = $request->user('user') ?? $request->user('guest') ?? null;

        if ($user) {
            $userType = $user instanceof User ? User::class : GuestUser::class;
            $user->load('role');
        } else {
            $userType = null;
        }

        $authPayload = [
            'user' => $user,
            'object_type' => $userType,
            'top_module_items' => ModuleItemResource::collection(ModuleItem::where('module_id', null)->get())->toArray($request),
        ];

        if ($user) {
            $userProfileService = app(UserProfileService::class);
            $lastUsedProfile = $userProfileService->getLastUsedProfile($user);
            $firstItemUrl = $userProfileService->getFirstItemUrl($lastUsedProfile);
            $authPayload['first_item_url'] = $firstItemUrl;
            $authPayload['active_profile'] = UserProfileResource::make($lastUsedProfile)->toArray($request);
            $authPayload['profiles'] = $userProfileService->getUserProfiles($user);
        }

        // dd($authPayload);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => $authPayload,
        ];
    }
}
