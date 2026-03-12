<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Entities\ObjectTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectTableRequest;
use App\Http\Resources\ModuleItemResource;
use App\Models\GuestUser;
use App\Models\ModuleItem;
use App\Models\User;
use App\Services\UserProfileService;
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

        $modulesTable = null;

        if ($tab === 'modules') {
            $activeProfile = $this->userProfileService->getLastUsedProfile($user);
            $language = $activeProfile->language;

            $modulesTable = (new ObjectTable(ModuleItem::class))
                ->fromRequest($validated)
                ->setColumns([
                    'name' => 'Naam',
                    'icon' => 'Pictogram',
                    'position' => 'Positie',
                ])
                ->usingResource(ModuleItemResource::class)
                ->usingTransformer(static function (array $row) use ($language): array {
                    /** @var array<string, mixed> $row */
                    if (isset($row['name']) && is_array($row['name'])) {
                        $row['name'] = $row['name'][$language] ?? reset($row['name']);
                    }

                    return $row;
                })
                ->getResult();
        }

        return Inertia::render('panel/settings/Index', [
            'activeTab' => $tab,
            'modulesTable' => $modulesTable,
        ]);
    }
}
