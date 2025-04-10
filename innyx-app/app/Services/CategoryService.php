<?php

namespace App\Services;

use App\Data\Category\CategoryData;
use App\Models\Category;

class CategoryService
{
    public function list(): array
    {
        return CategoryData::collect(Category::all())->toArray();
    }

    public function store(array $data): CategoryData
    {
        return CategoryData::from(Category::create($data));
    }

    public function delete(int $id)
    {
        return Category::find($id)->delete();
    }
}
