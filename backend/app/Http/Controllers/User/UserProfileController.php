<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\GuestUser;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService
    ) {}

    public function index(): InertiaResponse
    {
        return Inertia::render('user/profile/index');
    }

    public function update(UserProfileUpdateRequest $request, UserProfile $userProfile): RedirectResponse
    {
        Gate::authorize('update', $userProfile);

        $userProfile->update($request->validated());

        return redirect()->route('user.profile.index', $userProfile);
    }

    public function switch(Request $request, UserProfile $userProfile): JsonResponse
    {
        Gate::authorize('update', $userProfile);

        /** @var User|GuestUser|null $user */
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $this->userProfileService->switchProfile($user, $userProfile);

        return response()->json([
            'message' => 'Profile switched successfully',
        ]);
    }
}
