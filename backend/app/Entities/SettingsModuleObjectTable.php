<?php

declare(strict_types=1);

namespace App\Entities;

use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SettingsModuleObjectTable extends ObjectTable
{
    public function __construct(
        string $language
    ) {
        /** @var Builder<Model> */
        $builder = Module::select('id', 'name', 'icon', 'created_at', 'updated_at');

        parent::__construct(Module::class);
        $this->setBuilder($builder);
        $this->setColumns([
            'name' => 'Naam',
            'icon' => 'Pictogram',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Laast bewerkt op',
        ]);
        $this->usingResource(ModuleResource::class);
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

            $row['url'] = route('panel.settings.modules.edit', ['module' => $row['id']]);

            return $row;
        });
    }
}
