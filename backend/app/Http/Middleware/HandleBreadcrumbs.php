<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class HandleBreadcrumbs
{
    /**
     * Build breadcrumb items from the current request path.
     * Path segments that are route parameters and resolve to models get the model's display name.
     *
     * @return array{items: array<int, array{label: string, url: string}>, title: string}
     */
    public function getBreadcrumbs(Request $request): array
    {
        $path = trim($request->path(), '/');
        if ($path === '') {
            return [];
        }

        $segments = explode('/', $path);
        $route = $request->route();
        $uriParts = $route !== null ? explode('/', trim($route->uri(), '/')) : [];

        $breadcrumbs = [];
        $accumulated = [];

        foreach ($segments as $index => $segment) {
            $accumulated[] = $segment;
            $url = '/'.implode('/', $accumulated);
            $label = $this->resolveSegmentLabel($segment, $index, $uriParts, $route);

            if ($label === 'Panel') {
                continue;
            }

            if ($index === count($segments) - 1) {
                $title = $label;
            } else {
                $breadcrumbs[] = [
                    'label' => $label,
                    'url' => $url,
                ];
            }
        }

        return [
            'items' => $breadcrumbs,
            'title' => $title,
        ];
    }

    /**
     * Resolve the display label for a path segment.
     * If the segment is a route parameter bound to a model, use the model's name/title.
     *
     * @param  array<int, string>  $uriParts
     */
    private function resolveSegmentLabel(string $segment, int $index, array $uriParts, ?Route $route): string
    {
        $parameterName = $uriParts[$index] ?? null;

        if (
            $route !== null
            && $parameterName !== null
            && str_starts_with($parameterName, '{')
            && str_ends_with($parameterName, '}')
        ) {
            $paramKey = trim($parameterName, '{}');
            $paramKey = Str::before($paramKey, '?'); // optional params like {module?}

            if (!$route->hasParameter($paramKey)) {
                return $this->formatSegment($segment);
            }

            $value = $route->parameter($paramKey);

            if (is_object($value) && method_exists($value, 'getAttribute')) {
                /** @var string|array<string, string>|null $name */
                $name = $value->getAttribute('name')
                    ?? $value->getAttribute('title');

                if ($name !== null) {
                    if (is_array($name)) {
                        /** @var array<string, string> $name */
                        $singleName = $name['en'] ?? reset($name);

                        return (string) $singleName;
                    }

                    return (string) $name;
                }
            }
        }

        return $this->formatSegment($segment);
    }

    /**
     * Format a segment.
     */
    private function formatSegment(string $segment): string
    {
        if (Str::isUuid($segment)) {
            return $segment;
        }

        return str_replace('-', ' ', ucfirst($segment));
    }
}
