<?php

namespace App\Services;

use App\Data\Category\CategoryData;
use App\Models\Category;

class CategoryService
{
    public function list(): array
    {
        return CategoryData::collection(Category::all())->items();
    }

    public function store(array $data): CategoryData
    {
        return CategoryData::from(Category::create($data));
    }
}
