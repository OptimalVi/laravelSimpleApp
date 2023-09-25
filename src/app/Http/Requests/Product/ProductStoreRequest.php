<?php

namespace App\Http\Requests\Product;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'is_published' => 'boolean',
            'categories' => [
                'required', 'array', 'between:2,100',
                new Exists(Category::class, 'id')
            ],
        ];
    }
}
