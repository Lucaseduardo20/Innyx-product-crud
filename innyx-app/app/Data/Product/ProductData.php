<?php

namespace App\Data\Product;

use Spatie\LaravelData\Data;
use App\Models\Product;
use Spatie\LaravelData\Attributes\MapInputName;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public string|null $valid_until,
        public string|null $image,
        public string $category_id,
        public ?string $category_name,
        public string|null $user_name
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            description: $product->description,
            price: $product->price,
            image: $product->image,
            valid_until: $product->valid_until,
            category_id: $product->category->id,
            category_name: $product->category->name,
            user_name: auth()->user()->is_admin ? $product->user->name : null
        );
    }
}
