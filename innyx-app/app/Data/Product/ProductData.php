<?php

namespace App\Data\Product;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public string|null $valid_until,
        public string|null $image_path,
        public string $category_id,
    ) {}
}
