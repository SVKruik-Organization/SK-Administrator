<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\GuestUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\GuestUser
 */
class GuestUserResource extends JsonResource
{
    /** @var GuestUser */
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
            'first_name' => $this->resource->first_name,
            'middle_name' => $this->resource->middle_name,
            'last_name' => $this->resource->last_name,
            'full_name' => $this->resource->full_name,
            'email' => $this->resource->email,
            'last_login_at' => $this->resource->last_login_at,
            'image_url' => $this->resource->profileImage?->url,
        ];
    }
}
