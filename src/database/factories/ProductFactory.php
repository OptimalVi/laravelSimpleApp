<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    use WithFaker;

    public function definition(): array
    {
        return [
            'name' => $this->faker()->name(),
            'price' => $this->faker()->numerify('######.##'),
            'is_published' => true,
        ];
    }

    public function unPublished(): static
    {
        return $this->state([
            'is_published' => false,
        ]);
    }
}
