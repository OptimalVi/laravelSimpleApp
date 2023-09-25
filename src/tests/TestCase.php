<?php

namespace Tests;

use App\Models\Category;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createCategries(int $count): array
    {
        return Category::factory($count)->create()->pluck('id')->toArray();
    }
}
