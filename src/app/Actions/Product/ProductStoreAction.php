<?php

namespace App\Actions\Product;

use App\DTO\Product\ProductStoreDTO;
use App\Models\Product;

class ProductStoreAction
{
    public function run(ProductStoreDTO $dto): Product
    {
        $product = Product::create((array)$dto);
        $product->categories()->attach($dto->categories);
        return $product->refresh();
    }
}
