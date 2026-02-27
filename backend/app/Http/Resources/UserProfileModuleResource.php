<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\UserProfileModule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\UserProfileModule
 */
class UserProfileModuleResource extends JsonResource
{
    /** @var UserProfileModule */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource->module === null) {
            return [
                'id' => $this->resource->id,
                'name' => null,
                'icon' => null,
                'items' => [],
                'url' => null,
            ];
        }

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->module->name,
            'icon' => $this->resource->module->icon,
            'items' => ModuleItemResource::collection($this->resource->module->moduleItems)->toArray($request),
            'url' => $this->resource->module->panelUrl(),
        ];
    }
}
