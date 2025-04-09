<?php

use App\Services\CategoryService;
use App\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $service) {}

    public function index()
    {
        return response()->json($this->service->list());
    }

    public function store(StoreCategoryRequest $request)
    {
        return response()->json($this->service->store($request->validated()));
    }
}
