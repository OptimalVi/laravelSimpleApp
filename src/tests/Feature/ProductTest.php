<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use DB;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider createProductProvider
     */
    public function testCreateProduct(bool $isValid, array $customData): void
    {
        $data = array_merge(
            Product::factory()->makeOne()->getAttributes(),
            $customData,
            ['categories' => $this->createCategries(2)]
        );

        $response = $this->postJson('/products', $data);
        if (!$isValid) {
            $response->assertInvalid(array_keys($customData));
            return;
        }
        $response->assertCreated();
    }

    /**
     * @dataProvider createProductWithCategoriesProvider
     */
    public function testCreateProductCategoryAttachment(bool $isValid, int $categoriesCount): void
    {
        $data = array_merge(
            Product::factory()->makeOne()->getAttributes(),
            ['categories' => $this->createCategries($categoriesCount)]
        );

        $response = $this->postJson('/products', $data);
        if (!$isValid) {
            $response->assertInvalid('categories');
            return;
        }
        $response->assertCreated();
    }

    public function testUpdateProductSyncCategories(): void
    {
        $product = Product::factory()->createOne();
        $product->categories()->attach($this->createCategries(2));

        $updatedCategories = $this->createCategries(3);

        $this->put(
            sprintf('/products/%d', $product->getKey()),
            $product->getAttributes() + ['categories' => $updatedCategories]
        )->assertOk();

        $this->assertEquals(
            $updatedCategories,
            $product->categories->pluck('id')->toArray()
        );
    }

    public function testDeleteProduct(): void
    {
        $product = Product::factory()->createOne();

        $this->deleteJson(sprintf('/products/%d', $product->getKey()))
            ->assertOk();

        $this->assertTrue($product->refresh()->trashed());
    }

    public static function createProductProvider(): Generator
    {
        yield 'Valid: with price' => [true, ['price' => 200.20]];
        yield 'Valid: unpublished' => [true, ['is_published' => false]];
        yield 'Invalid: without name' => [false, ['name' => '']];
        yield 'Invalid: without price' => [false, ['price' => '']];
    }

    public static function createProductWithCategoriesProvider(): Generator
    {
        yield 'Invalid: without categories' => [false, 0];
        yield 'Invalid: with 1 categories' => [false, 1];
        yield 'Valid: With 2 categories' => [true, 2];
        yield 'Valid: With 50 categories' => [true, 50];
        yield 'Valid: With 100 categories' => [true,  100];
        yield 'Invalid: With 101 categories' => [false,  01];
    }
}
