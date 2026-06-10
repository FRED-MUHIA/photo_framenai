<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOptionGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTextFieldCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_text_field_is_saved_to_cart(): void
    {
        $category = Category::query()->create([
            'name' => 'Banners',
            'slug' => 'banners',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Vinyl Mesh Banner',
            'slug' => 'vinyl-mesh-banner',
            'base_price' => 500,
            'min_quantity' => 1,
            'base_delivery_days' => 3,
            'is_active' => true,
        ]);

        ProductOptionGroup::query()->create([
            'product_id' => $product->id,
            'name' => 'Banner Text',
            'code' => 'banner_text',
            'input_type' => 'text',
            'is_required' => true,
            'sort_order' => 0,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'custom_fields' => [
                'banner_text' => 'Grand Opening',
            ],
        ])->assertRedirect(route('cart.index'));

        $cart = session('print_cart');

        $this->assertSame('Banner Text', $cart[0]['custom_fields'][0]['label']);
        $this->assertSame('Grand Opening', $cart[0]['custom_fields'][0]['value']);
    }
}
