<?php

declare(strict_types=1);

namespace App\Entities;

use App\Types\OrderedTableData;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderedTable
{
    private Model $model;

    private int $totalRecords;

    /** @var array<string, string> */
    private array $columns;

    private Closure $mapper;

    /** @var Builder<Model> */
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
    public function getResult(): OrderedTableData
    {
        $rows = $this->builder->get();

        $this->totalRecords = $rows->count();

        $mapper = $this->mapper ?? static fn (Model $model): Model => $model;

        return new OrderedTableData([
            'data' => array_map($mapper, $rows->all()),
            'columns' => $this->getFormattedColumns(),
            'totalRecords' => $this->totalRecords,
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
     *
     * @return string The normalized column key.
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
