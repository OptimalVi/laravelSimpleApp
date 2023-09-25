<?php

namespace App\DTO\Product;

class ProductStoreDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?bool $is_published,
        public readonly array $categories,
    ) {
    }
}
