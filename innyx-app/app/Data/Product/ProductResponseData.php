<?php

namespace App\Data\Product;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use App\Models\Product;

class ProductResponseData extends Data
{
    public function __construct(
        public array $products,
        public int $current_page,
        public int $total,
        public int $last_page
    ) {}
}
