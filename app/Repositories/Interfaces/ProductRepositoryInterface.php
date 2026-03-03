<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll(array $filters = []);  
      public function create(array $data);
    public function getById($id);
    public function update($id, array $data);
    public function delete($id);
}