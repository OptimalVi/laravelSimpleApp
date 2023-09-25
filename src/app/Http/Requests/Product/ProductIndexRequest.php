<?php

namespace App\Http\Requests\Product;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class ProductIndexRequest extends FormRequest
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
            'name' => 'string',
            'category_id' => new Exists(Category::class, 'id'),
            'category_name' => 'string',
            'prices' => 'string|regex:/^\d+(\.\d{})?,\d+(\.\d+)?$/',
            'is_published' => 'bool',
        ];
    }
}
