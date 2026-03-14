<?php

declare(strict_types=1);

namespace App\Entities;

use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Carbon\Carbon;

class SettingsModuleObjectTable extends ObjectTable
{
    public function __construct(
        string $language
    ) {
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
                $row['created_at'] = Carbon::parse($row['created_at'])->format('d-m-Y H:i:s');
            }

            if (isset($row['updated_at'])) {
                $row['updated_at'] = Carbon::parse($row['updated_at'])->format('d-m-Y H:i:s');
            }

            // dd($row);
            $row['url'] = route('panel.settings.modules.show', ['module' => $row['id']]);

            return $row;
        });
    }
}
