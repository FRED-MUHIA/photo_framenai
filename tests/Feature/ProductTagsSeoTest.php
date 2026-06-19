<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTagsSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_tags_are_visible_and_available_to_crawlers(): void
    {
        $category = Category::query()->create([
            'name' => 'Banners',
            'slug' => 'banners',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Rollup Banner',
            'slug' => 'rollup-banner',
            'base_price' => 2500,
            'min_quantity' => 1,
            'base_delivery_days' => 2,
            'metadata' => [
                'tags' => ['rollup banner printing', 'event banners'],
            ],
            'is_active' => true,
        ]);

        $product->categories()->sync([$category->id]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('<meta name="keywords" content="rollup banner printing, event banners">', false)
            ->assertSee('rel="tag"', false)
            ->assertSee('rollup banner printing')
            ->assertSee('event banners')
            ->assertSee('"keywords": "rollup banner printing, event banners"', false);
    }
}
