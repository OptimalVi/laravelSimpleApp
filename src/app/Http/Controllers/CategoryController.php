<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CategoryController extends Controller
{

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->only(['name']));
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() !== 0) {
            throw new ConflictHttpException("Category has products, please unattach products");
        }
        $category->deleteOrFail();
    }
}
