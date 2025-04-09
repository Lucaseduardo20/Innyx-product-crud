<?php

use App\Data\ProductData;
use App\Models\Product;

class ProductService
{
    public function list(): array
    {
        return ProductData::collection(
            Product::with('category')->latest()->paginate(10)
        )->items();
    }

    public function store(array $data): ProductData
    {
        if (isset($data['image'])) {
            $data['image_path'] = $data['image']->store('products', 'public');
        }

        $product = Product::create($data);

        return ProductData::from($product->load('category'));
    }

    public function update(Product $product, array $data): ProductData
    {
        if (isset($data['image'])) {
            $data['image_path'] = $data['image']->store('products', 'public');
        }

        $product->update($data);

        return ProductData::from($product->load('category'));
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function show(Product $product): ProductData
    {
        return ProductData::from($product->load('category'));
    }
}
