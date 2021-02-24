<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

abstract class BaseRepository implements BaseRepositoryInterface
{
    private $model;

    /**
     * @param mixed $model
     */
    protected function setModel($model): void
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Lấy thông tin 1 bản ghi xác định bởi ID
     * @return mixed
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    public function getByQuery(array $params = [], $size = 20)
    {
        $sort = Arr::get($params, 'sort', 'created_at:-1');
        $params['sort'] = $sort;
        $lModel = $this->getModel();
        $params = Arr::except($params, ['page', 'limit']);
        if (count($params)) {
            $reflection = new \ReflectionClass($lModel);
            foreach ($params as $funcName => $funcParams) {
                $funcName = \Illuminate\Support\Str::studly($funcName);
                if ($reflection->hasMethod('scope' . $funcName) && $funcParams) {
                    $funcName = lcfirst($funcName);
                    $lModel = $lModel->$funcName($funcParams);
                }
            }
        }
        switch ($size) {
            case -1:
                return $lModel->get();
            case 0:
                return $lModel->first();
            default:
                return $lModel->paginate($size);
        }
    }

    public function store(array $data)
    {
        $fillable = $this->getModel()->getFillable();

        return $this->getModel()->create(Arr::only($data, $fillable));
    }
}
