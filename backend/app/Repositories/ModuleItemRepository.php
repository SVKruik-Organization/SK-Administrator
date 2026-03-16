<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Module;
use App\Models\ModuleItem;

class ModuleItemRepository extends AbstractRepository
{
    public function __construct(ModuleItem $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the next position for a module item.
     */
    public function getNextPosition(Module $module): int
    {
        return ModuleItem::where('module_id', $module->id)->max('position') + 1;
    }
}
