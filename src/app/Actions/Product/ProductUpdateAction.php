<?php

namespace App\Actions\Product;

use App\DTO\Product\ProductStoreDTO;
use App\Models\Product;

class ProductUpdateAction
{
    public function run(Product $product, ProductStoreDTO $dto): Product
    {
        $product->update((array)$dto);
        $product->categories()->sync($dto->categories);

        return $product->refresh();
    }
}
