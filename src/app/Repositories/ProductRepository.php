<?php

namespace App\Repositories;

use App\DTO\Product\ProductFilterDTO;
use App\Helpers\DBHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getList(ProductFilterDTO $filter): Collection
    {
        $products = Product::query();

        if (isset($filter->name)) {
            $products->where(
                'name',
                'ilike',
                DBHelper::stringToLikeAnyWords($filter->name, true)
            );
        }

        if (isset($filter->category_id)) {
            $products->whereHas('categories', fn ($q) => $q->where('category_id', $filter->category_id));
        }

        if (isset($filter->category_name)) {
            $products->whereHas(
                'categories',
                fn ($q) => $q->where(
                    'name',
                    'ilike',
                    DBHelper::stringToLikeAnyWords($filter->category_name, true)
                )
            );
        }

        if (isset($filter->prices)) {
            $products->whereBetween('price', $filter->prices());
        }

        if (isset($filter->is_published)) {
            $filter->is_published ? $products->published() : $products->unPublished();
        }

        return $products->get();
    }
}
