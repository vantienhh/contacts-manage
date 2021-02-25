<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public function getModel(): Model|Builder;

    public function getById(mixed $id): Model|Collection|static;

    public function getByQuery(array $params = [], $limit = 20);

    public function store(array $params): Model|static;
}
