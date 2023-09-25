<?php

namespace App\Http\Controllers;

use App\Actions\Product\ProductStoreAction;
use App\Actions\Product\ProductUpdateAction;
use App\DTO\Product\ProductFilterDTO;
use App\DTO\Product\ProductStoreDTO;
use App\Helpers\DBHelper;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use DB;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $products = app(ProductRepository::class)->getList(
            new ProductFilterDTO($request->all())
        );
        return ProductResource::collection($products);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = app(ProductStoreAction::class)->run(
            new ProductStoreDTO(...$request->validated())
        );

        return new ProductResource($product);
    }

    public function update(ProductStoreRequest $request, Product $product)
    {
        $product = app(ProductUpdateAction::class)->run(
            $product,
            new ProductStoreDTO(...$request->validated())
        );

        return new ProductResource($product);
    }

    public function destroy(Product $product): bool
    {
        return $product->delete();
    }
}
