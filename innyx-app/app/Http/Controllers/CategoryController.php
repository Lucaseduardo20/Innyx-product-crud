<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\Request;


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

    public function destroy(Request $request)
    {
        return response()->json($this->service->delete($request->id));
    }
}
