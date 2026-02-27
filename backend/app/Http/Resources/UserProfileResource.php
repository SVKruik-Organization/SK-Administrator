<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /** @var UserProfile */
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
            'description' => $this->resource->description,
            'position' => $this->resource->position,
            'language' => $this->resource->language,
            'last_usage_at' => $this->resource->last_usage_at,
            'modules' => UserProfileModuleResource::collection($this->resource->userProfileModules)->toArray($request),
        ];
    }
}
