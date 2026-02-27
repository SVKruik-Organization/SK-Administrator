<?php

declare(strict_types=1);

namespace App\Traits;

trait HasPanelUrl
{
    /**
     * Get the path segments that come after `/panel`.
     *
     * @return array<int, string>
     */
    abstract protected function panelPathSegments(): array;

    public function panelUrl(): string
    {
        return strtolower('/panel/' . implode('/', $this->panelPathSegments()));
    }
}
