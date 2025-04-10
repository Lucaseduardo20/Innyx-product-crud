<?php

namespace App\Services;

use App\Data\Product\ProductData;
use App\Data\Product\ProductResponseData;
use App\Models\Product;

class ProductService
{
    public function list(int $page = 1): ProductResponseData
    {
        $paginator = Product::with('category')
            ->latest()
            ->paginate(perPage: 10, page: $page);

            return new ProductResponseData(
                products: ProductData::collect($paginator->items()),
                current_page: $paginator->currentPage(),
                total: $paginator->total(),
                last_page: $paginator->lastPage()
            );
    }

    public function store(array $data): ProductData
    {
        if (isset($data['image_path'])) {
            $path = $data['image_path']->store('products', 'public');
            $data['image_path'] = "storage/{$path}";
        }


        $product = new Product();
        $product->user_id = $data['user_id'];
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->image = $data['image_path'] ?? null;
        $product->valid_until = $data['valid_until'] ?? null;
        $product->save();

        return ProductData::from($product->load('category'));
    }

    public function update(Product $product, array $data): ProductData
    {
        if (isset($data['image'])) {
            $path = $data['image']->store('products', 'public');
            $data['image_path'] = "storage/{$path}";
        }

        logger('data', [$data]);
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
