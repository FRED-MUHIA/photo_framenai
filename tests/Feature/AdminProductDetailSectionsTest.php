<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductDetailSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_product_specific_detail_sections(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::query()->create([
            'name' => 'Banners',
            'slug' => 'banners',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_ids' => [$category->id],
                'name' => 'Rollup Banner',
                'slug' => 'rollup-banner',
                'sku' => 'ROLL-001',
                'base_price' => 2500,
                'min_quantity' => 1,
                'base_delivery_days' => 2,
                'short_description' => 'Portable banner.',
                'description' => 'Custom rollup banner.',
                'metadata' => [
                    'upload_enabled' => '1',
                    'detail_sections' => [
                        ['title' => 'Banner stand details', 'content' => 'Includes stand, print, and carrier bag.'],
                        ['title' => 'Artwork setup', 'content' => 'Send print-ready artwork with bleed.'],
                    ],
                ],
                'is_featured' => '0',
                'is_active' => '1',
                'option_groups_json' => '[]',
                'quantity_prices_json' => '[]',
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'rollup-banner')->firstOrFail();

        $this->assertSame('Banner stand details', data_get($product->metadata, 'detail_sections.0.title'));
        $this->assertSame('Includes stand, print, and carrier bag.', data_get($product->metadata, 'detail_sections.0.content'));

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Banner stand details')
            ->assertSee('Includes stand, print, and carrier bag.')
            ->assertDontSee('What is included with this product?');
    }
}
