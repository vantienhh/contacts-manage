<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getModel();

    public function getById($id);

    public function getByQuery(array $params = [], $limit = 20);

    public function store(array $params);
}
