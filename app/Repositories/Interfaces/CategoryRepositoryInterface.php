<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAll();
    public function create(array $data);
}