<?php

namespace App\Services;

use App\Data\Product\ProductData;
use App\Data\Product\ProductResponseData;
use App\Models\Product;

class ProductService
{
    public function list(int $page = 1, ?string $search = ''): ProductResponseData
    {
        $query = Product::with('category')
            ->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $paginator = $query->paginate(perPage: 10, page: $page);

        $productsData = collect($paginator->items())
            ->map(fn ($product) => ProductData::fromModel($product))
            ->all();

        return new ProductResponseData(
            products: $productsData,
            current_page: $paginator->currentPage(),
            total: $paginator->total(),
            last_page: $paginator->lastPage()
        );
    }



    public function store(array $data): ProductData
    {
        if (isset($data['image'])) {
            $path = $data['image']->store('products', 'public');
            $data['image'] = "storage/{$path}";
        }


        $product = new Product();
        $product->user_id = $data['user_id'];
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->image = $data['image'] ?? null;
        $product->valid_until = $data['valid_until'] ?? null;
        $product->save();

        return ProductData::from($product->load('category'));
    }

    public function update(Product $product, array $data): ProductData
    {
        if (isset($data['image'])) {
            $path = $data['image']->store('products', 'public');
            $data['image'] = "storage/{$path}";
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
