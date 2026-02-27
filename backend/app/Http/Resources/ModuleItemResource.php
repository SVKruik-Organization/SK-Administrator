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
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'icon' => $this->resource->icon,
            'position' => $this->resource->position,
            'url' => $this->resource->panelUrl(),
        ];
    }
}
