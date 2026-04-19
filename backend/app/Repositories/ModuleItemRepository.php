<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Module;
use App\Models\ModuleItem;

/** 
 * @extends AbstractRepository<ModuleItem>
 */
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
        /** @var ModuleItem|null $highestPosition */
        $highestPosition = $this->getModel()->where('module_id', $module->id)->orderBy('position', 'desc')->first();

        if ($highestPosition) {
            return $highestPosition->position + 1;
        }
        return 1;
    }
}
