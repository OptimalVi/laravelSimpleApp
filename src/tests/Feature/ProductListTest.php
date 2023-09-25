<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    private Product $controlProduct;

    public function requestList(array $filter = []): TestResponse
    {
        $response = $this->get(route('products.index', $filter));
        $response->isServerError() && dump($response);
        return $response->assertOk();
    }

    /**
     * A basic feature test example.
     */
    public function testList(): void
    {
        Product::factory(3)->create();
        $this->requestList()->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /**
     * @dataProvider searchByNameProvider
     */
    public function testByName(bool $isValid, string $search): void
    {
        Product::factory(3)->create();
        Product::factory()->create(['name' => 'Тестовое Название']);

        $response = $this->requestList(['name' => $search])
            ->assertOk();

        if ($isValid) {
            $response->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Тестовое Название');
            return;
        }
        $response->assertJsonCount(0, 'data');
    }

    public static function searchByNameProvider(): array
    {
        return [
            [true, 'стов'],
            [true, 'Название'],
            [true, 'Тестовое'],
            [true, 'Тестовое Название'],
            [false, 'oaheint'],
        ];
    }

    public function testByCategoryId(): void
    {
        $controlCategories = $this->createCategries(2);
        $withoutProducts = $this->createCategries(1)[0];
        $products = Product::factory(4)->create();
        $products->slice(0, 2)->each(
            fn (Product $product) => $product->categories()->attach($controlCategories)
        );
        $products->slice(2, 2)->each(
            fn (Product $product) => $product->categories()->attach($this->createCategries(2))
        );

        $this->requestList(['category_id' => $controlCategories[0]])
            ->assertJsonCount(2, 'data');

        $this->requestList(['category_id' => $withoutProducts])
            ->assertJsonCount(0, 'data');
    }

    public function testByCategoryName(): void
    {
        $controlCategory = Category::factory()->createOne(['name' => 'Продукт']);
        Category::factory()->createOne(['name' => 'Без']);
        $products = Product::factory(4)->create();
        $products->slice(0, 2)->each(
            fn (Product $product) => $product->categories()
                ->attach(array_merge($this->createCategries(2), [$controlCategory->getKey()]))
        );
        $products->slice(2, 2)->each(
            fn (Product $product) => $product->categories()->attach($this->createCategries(2))
        );

        $this->requestList(['category_name' => 'укт'])
            ->assertJsonCount(2, 'data');

        $this->requestList(['category_name' => 'Без'])
            ->assertJsonCount(0, 'data');
    }

    public function testByPrices(): void
    {
        $products = Product::factory(5)
            ->sequence(
                ['price' => 100.10],
                ['price' => 124.60],
                ['price' => 200.00],
                ['price' => 201.10],
                ['price' => 50.10],
            )
            ->create();

        $this->requestList(['prices' => '100,200'])
            ->assertJsonCount(3, 'data');
    }

    public function testByPublised(): void
    {
        $published = Product::factory(2)->create();
        $unPublished = Product::factory(3)->unPublished()->create();

        $this->requestList(['is_published' => 1])
            ->assertJsonCount($published->count(), 'data');

        $this->requestList(['is_published' => 0])
            ->assertJsonCount($unPublished->count(), 'data');
    }
}
