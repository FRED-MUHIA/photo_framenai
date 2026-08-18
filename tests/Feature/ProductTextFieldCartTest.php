<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOptionGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    public function test_delivery_date_does_not_block_cart_price_calculation(): void
    {
        Carbon::setTestNow('2026-08-18 10:00:00');

        $category = Category::query()->create([
            'name' => 'Flyers',
            'slug' => 'flyers',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'A5 Flyers',
            'slug' => 'a5-flyers',
            'base_price' => 90,
            'min_quantity' => 1,
            'base_delivery_days' => 3,
            'is_active' => true,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ])->assertRedirect(route('cart.index'));

        $cart = session('print_cart');

        $this->assertSame(3, $cart[0]['quantity']);
        $this->assertSame(90.0, $cart[0]['unit_price']);
        $this->assertSame(270.0, $cart[0]['line_total']);
        $this->assertSame('2026-08-24', $cart[0]['delivery_date']);
    }

    public function test_next_day_delivery_automatically_uses_rush_fee(): void
    {
        Carbon::setTestNow('2026-08-18 10:00:00');
        config(['shop.rush_order_fee' => 500]);

        $category = Category::query()->create([
            'name' => 'Flyers',
            'slug' => 'next-day-flyers',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Next Day Flyers',
            'slug' => 'next-day-flyers',
            'base_price' => 90,
            'min_quantity' => 1,
            'base_delivery_days' => 3,
            'is_active' => true,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
            'delivery_date' => '2026-08-19',
        ])->assertRedirect(route('cart.index'));

        $cart = session('print_cart');

        $this->assertSame(90.0, $cart[0]['unit_price']);
        $this->assertSame(770.0, $cart[0]['line_total']);
        $this->assertSame('2026-08-19', $cart[0]['delivery_date']);
        $this->assertTrue($cart[0]['rush_order']);
        $this->assertSame(500.0, $cart[0]['rush_fee']);
    }

    public function test_rush_order_adds_fee_and_allows_same_day_delivery(): void
    {
        Carbon::setTestNow('2026-08-18 10:00:00');
        config(['shop.rush_order_fee' => 500]);

        $category = Category::query()->create([
            'name' => 'Flyers',
            'slug' => 'same-day-flyers',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Same Day Flyers',
            'slug' => 'same-day-flyers',
            'base_price' => 90,
            'min_quantity' => 1,
            'base_delivery_days' => 3,
            'is_active' => true,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
            'rush_order' => '1',
            'delivery_date' => '2026-08-18',
        ])->assertRedirect(route('cart.index'));

        $cart = session('print_cart');

        $this->assertSame(90.0, $cart[0]['unit_price']);
        $this->assertSame(770.0, $cart[0]['line_total']);
        $this->assertSame(0, $cart[0]['delivery_days']);
        $this->assertSame('2026-08-18', $cart[0]['delivery_date']);
        $this->assertTrue($cart[0]['rush_order']);
        $this->assertSame(500.0, $cart[0]['rush_fee']);
    }
}
