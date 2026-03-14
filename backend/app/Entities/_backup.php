<?php

declare(strict_types=1);

namespace App\Entities;

use App\Http\Resources\ModuleItemResource;
use App\Models\ModuleItem;

class BackupObjectTable extends ObjectTable
{
    public function __construct(
        string $language
    ) {
        $builder = ModuleItem::query()
            ->join('modules', 'module_items.module_id', '=', 'modules.id')
            ->orderBy('module_items.position');

        parent::__construct(ModuleItem::class);
        $this->setBuilder($builder);
        $this->setColumns([
            'module_items.name' => 'Naam',
            'modules.icon' => 'Pictogram',
            'module_items.position' => 'Positie',
            'modules.name as module_name' => 'Module',
        ]);
        $this->usingResource(ModuleItemResource::class);
        $this->usingTransformer(static function (array $row) use ($language): array {
            /** @var array<string, mixed> $row */
            if (isset($row['name']) && is_array($row['name'])) {
                $row['name'] = $row['name'][$language] ?? reset($row['name']);
            }
            if (isset($row['module_name']) && is_array($row['module_name'])) {
                $row['module_name'] = $row['module_name'][$language] ?? reset($row['module_name']);
            }

            return $row;
        });
    }
}
