<?php

declare(strict_types=1);

namespace App\Entities\OrderedTables;

use App\Entities\OrderedTable;
use App\Http\Resources\ModuleItemResource;
use App\Models\Module;
use App\Models\ModuleItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ModuleItemOrderedTable extends OrderedTable
{
    public function __construct(
        Module $module,
        string $language
    ) {
        /** @var Builder<Model> */
        $builder = ModuleItem::select('id', 'module_id', 'name', 'position', 'created_at', 'updated_at')
            ->with('module')
            ->where('module_id', $module->id);

        parent::__construct(ModuleItem::class);
        $this->setBuilder($builder);
        $this->setColumns([
            'name' => 'Naam',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Laast bewerkt op',
        ]);
        $this->usingResource(ModuleItemResource::class);
        $this->usingTransformer(static function (array $row) use ($language): array {
            /** @var array<string, mixed> $row */
            if (isset($row['name']) && is_array($row['name'])) {
                $row['name'] = $row['name'][$language] ?? reset($row['name']);
            }

            if (isset($row['created_at'])) {
                /** @var Carbon $createdAt */
                $createdAt = $row['created_at'];
                $row['created_at'] = Carbon::parse($createdAt)->format('d-m-Y H:i:s');
            }

            if (isset($row['updated_at'])) {
                /** @var Carbon $updatedAt */
                $updatedAt = $row['updated_at'];
                $row['updated_at'] = Carbon::parse($updatedAt)->format('d-m-Y H:i:s');
            }

            $row['url'] = route('panel.settings.module-items.edit', [$row['module']['id'], $row['id']]);

            return $row;
        });
    }
}
