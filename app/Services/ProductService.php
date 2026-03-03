<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getAllProducts(array $filters = [])
    {
        return $this->productRepo->getAll($filters);
    }

    public function createProduct(array $data)
    {
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['image']->store('products', 'public');
            $data['image'] = $path;
        }

        return $this->productRepo->create($data);
    }
    public function getProductById($id)
    {
        return $this->productRepo->getById($id);
    }

    public function updateProduct($id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        return $this->productRepo->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepo->delete($id);
    }
}