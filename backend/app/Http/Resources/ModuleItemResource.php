<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ModuleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ModuleItem
 */
class ModuleItemResource extends JsonResource
{
    /** @var ModuleItem */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('module');
        $module = $this->resource->getAttribute('module');
        if (is_string($module)) {
            $module = json_decode($module, true);
        }

        return [
            'id' => $this->resource->id,
            'module' => $module,
            'name' => $this->resource->name,
            'icon' => $this->resource->icon,
            'position' => $this->resource->position,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
