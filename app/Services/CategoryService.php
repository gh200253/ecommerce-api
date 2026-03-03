<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getAllCategories()
    {
        return $this->categoryRepo->getAll();
    }

    public function createCategory(array $data)
    {
        // إنشاء الـ Slug تلقائياً من اسم القسم
        $data['slug'] = Str::slug($data['name']);
        
        return $this->categoryRepo->create($data);
    }
}