<?php

namespace App\DTO\Product;

class ProductFilterDTO
{
    public readonly ?string $name;
    public readonly ?int $category_id;
    public readonly ?string $category_name;
    public readonly ?string $prices;
    public readonly ?bool $is_published;

    public function __construct(array $attributes)
    {
        foreach (get_class_vars(static::class) as $name => $value) {
            if (isset($attributes[$name])) {
                $this->{$name} = $attributes[$name];
            }
        }
    }

    public function prices(): array
    {
        return isset($this->prices) ? explode(',', $this->prices) : null;
    }
}
