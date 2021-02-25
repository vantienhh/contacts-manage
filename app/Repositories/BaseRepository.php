<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->setModel($model);
    }

    protected function setModel(Model $model): void {
        $this->model = $model;
    }

    public function getModel(): Model|Builder
    {
        return $this->model;
    }

    /**
     * Lấy thông tin 1 bản ghi xác định bởi ID
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(mixed $id): Model|\Illuminate\Database\Eloquent\Collection|static
    {
        return $this->getModel()->findOrFail($id);
    }

    public function getByQuery(array $params = [], $size = 20)
    {
        $lModel = $this->getModel();
        $params = Arr::except($params, ['page', 'limit']);
        if (count($params)) {
            $reflection = new \ReflectionClass($lModel);
            foreach ($params as $funcName => $funcParams) {
                $funcName = Str::studly($funcName);
                if ($reflection->hasMethod('scope' . $funcName) && $funcParams) {
                    $funcName = lcfirst($funcName);
                    $lModel = $lModel->$funcName($funcParams);
                }
            }
        }

        return match ($size) {
            -1 => $lModel->get(),
            0 => $lModel->first(),
            default => $lModel->paginate($size),
        };
    }

    public function store(array $params): Model|static
    {
        $fillable = $this->getModel()->getFillable();

        return $this->getModel()->create(Arr::only($params, $fillable));
    }
}
