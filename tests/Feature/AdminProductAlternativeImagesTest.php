<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductAlternativeImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_two_product_alternative_images(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::query()->create([
            'name' => 'Flyers',
            'slug' => 'flyers',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_ids' => [$category->id],
                'name' => 'Premium Flyers',
                'slug' => 'premium-flyers',
                'sku' => 'FLY-PREM',
                'base_price' => 1200,
                'min_quantity' => 100,
                'base_delivery_days' => 3,
                'thumbnail' => '/storage/product-thumbnails/main.jpg',
                'short_description' => 'A premium flyer product.',
                'description' => 'Print-ready flyers.',
                'metadata' => [
                    'alternative_image_1' => '/storage/product-thumbnails/alt-one.jpg',
                    'alternative_image_2' => '/storage/product-thumbnails/alt-two.jpg',
                    'upload_enabled' => '1',
                ],
                'is_featured' => '0',
                'is_active' => '1',
                'option_groups_json' => '[]',
                'quantity_prices_json' => '[]',
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'premium-flyers')->firstOrFail();

        $this->assertSame('/storage/product-thumbnails/alt-one.jpg', data_get($product->metadata, 'alternative_image_1'));
        $this->assertSame('/storage/product-thumbnails/alt-two.jpg', data_get($product->metadata, 'alternative_image_2'));

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('/storage/product-thumbnails/alt-one.jpg')
            ->assertSee('/storage/product-thumbnails/alt-two.jpg');
    }
}
