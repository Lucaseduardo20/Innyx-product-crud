<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

class PaginatedResponseData extends Data
{
    public function __construct(
        public array $items,
        public int $current_page,
        public int $total,
        public int $last_page,
    ) {}
}
