<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Entities\SettingsModuleObjectTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectTableRequest;
use App\Models\GuestUser;
use App\Models\User;
use App\Models\Module;
use App\Services\UserProfileService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SettingsController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService
    ) {}

    public function index(ObjectTableRequest $request): InertiaResponse
    {
        /** @var User|GuestUser $user */
        $user = get_user() ?? abort(403, 'Unauthorized');

        /** @var array{page: int, perPage: int} $validated */
        $validated = $request->validated();

        $tab = $request->query('tab', 'modules');

        $table = null;
        $cta = null;

        if ($tab === 'modules') {
            Gate::authorize('viewAny', Module::class);

            $language = $this->userProfileService->getActiveProfileLanguage($user);

            $table = (new SettingsModuleObjectTable($language))
                ->fromRequest($validated)
                ->getResult();

            $cta = [
                'label' => 'Module toevoegen',
                'url' => route('panel.settings.modules.create'),
            ];
        }

        return Inertia::render('panel/settings/Index', [
            'activeTab' => $tab,
            'table' => $table,
            'cta' => $cta,
        ]);
    }
}
