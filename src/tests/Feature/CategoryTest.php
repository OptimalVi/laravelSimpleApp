<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testCreateCategory(): void
    {
        $category = Category::factory()->makeOne();
        $this->postJson('category', $category->getAttributes())
            ->assertCreated();
    }

    public function testDeleteCategory(): void
    {
        $category = Category::factory()->createOne();
        $this->deleteJson(sprintf('category/%d', $category->getKey()))
            ->assertOk();
    }

    public function testDeleteCategoryWithProduct(): void
    {
        $category = Category::factory()->createOne();
        $category->products()
            ->attach(Product::factory()->createOne()->getKey());

        $this->deleteJson(sprintf('category/%d', $category->getKey()))
            ->assertConflict();
    }
}
