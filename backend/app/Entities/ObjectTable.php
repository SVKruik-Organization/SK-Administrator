<?php

declare(strict_types=1);

namespace App\Entities;

use App\Types\ObjectTableData;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectTable
{
    private Model $model;

    private int $page;

    private int $perPage;

    private int $totalPages;

    private int $totalRecords;

    private bool $hasMore;

    /** @var array<string, string> */
    private array $columns;

    private Closure $mapper;

    private Builder $builder;

    /**
     * @param  class-string<Model>  $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->model = new $modelClass;
        $this->builder = $this->model->query();
    }

    /**
     * Get the model this object table is working on.
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set the pagination for the query.
     *
     * @param  array{page: int, perPage: int}  $validated
     */
    public function fromRequest(array $validated): self
    {
        $this->page = $validated['page'];
        $this->perPage = $validated['perPage'];

        return $this;
    }

    /**
     * Set the columns for the query.
     * These should only be database columns, not the resource columns.
     *
     * @param  array<string, string>  $columns
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Execute the query and return the formatted data.
     */
    public function getResult(): ObjectTableData
    {
        $dataColumns = array_keys($this->columns ?? []);
        $page = $this->page ?? 1;
        $perPage = $this->perPage ?? 15;

        $rows = $this->builder->paginate($perPage, $dataColumns, 'page', $page);

        $this->page = $rows->currentPage();
        $this->perPage = $rows->perPage();
        $this->totalRecords = $rows->total();
        $this->totalPages = (int) ceil($this->totalRecords / $this->perPage);
        $this->hasMore = $rows->hasMorePages();

        $mapper = $this->mapper ?? static fn (Model $model): Model => $model;

        return new ObjectTableData([
            'data' => array_map($mapper, $rows->items()),
            'columns' => $this->getFormattedColumns(),
            'page' => $this->page,
            'perPage' => $this->perPage,
            'totalRecords' => $this->totalRecords,
            'totalPages' => $this->totalPages,
            'hasMore' => $this->hasMore,
        ]);
    }

    /**
     * Set the mapper for the result.
     *
     * @param  Closure(Model): array<string, mixed>  $mapper
     */
    public function setMapper(Closure $mapper): self
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * Use a JSON resource to shape each row.
     *
     * @param  class-string<JsonResource>  $resourceClass
     */
    public function usingResource(string $resourceClass): self
    {
        return $this->setMapper(
            static function (Model $model) use ($resourceClass): array {
                /** @var JsonResource $resource */
                $resource = new $resourceClass($model);
                /** @var Request $request */
                $request = request();
                /** @var array<string, mixed> $data */
                $data = $resource->toArray($request);

                return (array) $data;
            }
        );
    }

    /**
     * Use a transformer to shape each row.
     *
     * @param  Closure(array<string,mixed>): array<string,mixed>  $transformer
     */
    public function usingTransformer(Closure $transformer): self
    {
        $previous = $this->mapper ?? static fn (Model $model): array => (array) $model;
        $this->mapper = static function (Model $model) use ($previous, $transformer): array {
            /** @var array<string, mixed> $base */
            $base = $previous($model);
            /** @var array<string, mixed> $transformed */
            $transformed = $transformer($base);

            return $transformed;
        };

        return $this;
    }

    /**
     * Sets the builder for the query.
     *
     * @param  Builder<Model>  $builder
     */
    public function setBuilder(Builder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Get the formatted columns with normalized keys for display and row indexing.
     * Qualified keys (e.g. "module_items.id") become the short name ("id").
     * Aliased keys (e.g. "modules.name as module_name") use the alias ("module_name").
     *
     * @return array<string, string>
     */
    public function getFormattedColumns(): array
    {
        $formatted = [];

        foreach ($this->columns as $key => $label) {
            $formatted[$this->normalizeColumnKey($key)] = $label;
        }

        return $formatted;
    }

    /**
     * Normalize a column key to the short form used in row data.
     */
    private function normalizeColumnKey(string $key): string
    {
        if (preg_match('/\s+as\s+(\w+)\s*$/i', $key, $matches)) {
            return $matches[1];
        }

        if (str_contains($key, '.')) {
            return substr($key, (int) strrpos($key, '.') + 1);
        }

        return $key;
    }
}
