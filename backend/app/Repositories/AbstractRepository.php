<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class AbstractRepository
{
    /** @var TModel */
    protected Model $model;

    /**
     * @param  TModel  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return TModel
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    public function getModelName(): string
    {
        return $this->model->getTable();
    }

    public function getModelClass(): string
    {
        return $this->model::class;
    }

    /**
     * Update a model.
     *
     * @param  TModel  $model  The model to update.
     * @param  array<string, mixed>  $data  The data to update the model with.
     * @return TModel The updated model.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model;
    }
}
