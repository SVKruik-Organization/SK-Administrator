<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Module;
use App\Repositories\ModuleItemRepository;

class ModuleItemService
{
    public function __construct(
        private ModuleItemRepository $moduleItemRepository
    ) {}

    /**
     * Get the next position for a module item.
     */
    public function getNextPosition(Module $module): int
    {
        return $this->moduleItemRepository->getNextPosition($module);
    }
}
