<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Requests\StoreProductRequest;
use App\Data\ProductData;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index()
    {
        return response()->json($this->service->list());
    }

    public function store(StoreProductRequest $request)
    {
        return response()->json($this->service->store($request->validated()));
    }

    public function show(Product $product)
    {
        return response()->json($this->service->show($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return response()->json($this->service->update($product, $request->validated()));
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return response()->noContent();
    }
}
