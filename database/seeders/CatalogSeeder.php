<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Business Cards',
                'slug' => 'business-cards',
                'icon' => 'credit-card',
                'description' => 'Premium custom business cards for professionals and brands.',
                'products' => [
                    [
                        'name' => 'Premium Business Cards',
                        'slug' => 'premium-business-cards',
                        'sku' => 'BC-PREM-001',
                        'short_description' => 'Matte or gloss cards with multiple finishes.',
                        'base_price' => 1800,
                        'is_featured' => true,
                        'thumbnail' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200&auto=format&fit=crop',
                        'options' => [
                            ['name' => 'Size', 'code' => 'size', 'values' => [['label' => 'A7', 'value' => 'A7', 'is_default' => true], ['label' => '90x50mm', 'value' => '90x50'], ['label' => 'Custom', 'value' => 'custom', 'price_modifier' => 4]]],
                            ['name' => 'Paper Type', 'code' => 'paper', 'values' => [['label' => '350gsm Matte', 'value' => '350gsm-matte', 'is_default' => true], ['label' => '400gsm Silk', 'value' => '400gsm-silk', 'price_modifier' => 3.5], ['label' => 'Recycled', 'value' => 'recycled', 'price_modifier' => 2.5]]],
                            ['name' => 'Finishing', 'code' => 'finishing', 'values' => [['label' => 'No Lamination', 'value' => 'none', 'is_default' => true], ['label' => 'Matte Lamination', 'value' => 'matte', 'price_modifier' => 2], ['label' => 'Gloss Lamination', 'value' => 'gloss', 'price_modifier' => 2]]],
                            ['name' => 'Print Sides', 'code' => 'sides', 'values' => [['label' => 'Single Side', 'value' => 'single', 'is_default' => true], ['label' => 'Double Side', 'value' => 'double', 'price_modifier' => 1.5]]],
                        ],
                        'quantities' => [
                            ['quantity' => 100, 'price' => 1800, 'discount_percentage' => 0, 'delivery_days' => 3],
                            ['quantity' => 250, 'price' => 3900, 'discount_percentage' => 6, 'delivery_days' => 3],
                            ['quantity' => 500, 'price' => 6900, 'discount_percentage' => 11, 'delivery_days' => 4],
                            ['quantity' => 1000, 'price' => 12200, 'discount_percentage' => 18, 'delivery_days' => 5],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Flyers & Brochures',
                'slug' => 'flyers-brochures',
                'icon' => 'document-text',
                'description' => 'Promotional and event print materials in multiple sizes.',
                'products' => [
                    [
                        'name' => 'A5 Promotional Flyers',
                        'slug' => 'a5-promotional-flyers',
                        'sku' => 'FLY-A5-002',
                        'short_description' => 'Bright full-color flyers ideal for campaigns.',
                        'base_price' => 3200,
                        'is_featured' => true,
                        'thumbnail' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?w=1200&auto=format&fit=crop',
                        'options' => [
                            ['name' => 'Size', 'code' => 'size', 'values' => [['label' => 'A6', 'value' => 'A6'], ['label' => 'A5', 'value' => 'A5', 'is_default' => true], ['label' => 'A4', 'value' => 'A4', 'price_modifier' => 5]]],
                            ['name' => 'Paper Type', 'code' => 'paper', 'values' => [['label' => '150gsm Gloss', 'value' => '150-gloss', 'is_default' => true], ['label' => '170gsm Silk', 'value' => '170-silk', 'price_modifier' => 1.5]]],
                            ['name' => 'Print Sides', 'code' => 'sides', 'values' => [['label' => 'Single Side', 'value' => 'single', 'is_default' => true], ['label' => 'Double Side', 'value' => 'double', 'price_modifier' => 1.2]]],
                        ],
                        'quantities' => [
                            ['quantity' => 500, 'price' => 3200, 'discount_percentage' => 0, 'delivery_days' => 3],
                            ['quantity' => 1000, 'price' => 5600, 'discount_percentage' => 9, 'delivery_days' => 4],
                            ['quantity' => 2500, 'price' => 11600, 'discount_percentage' => 15, 'delivery_days' => 5],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Posters',
                'slug' => 'posters',
                'icon' => 'rectangle-stack',
                'description' => 'Indoor and outdoor posters for campaigns and branding.',
            ],
            [
                'name' => 'Banners',
                'slug' => 'banners',
                'icon' => 'flag',
                'description' => 'PVC banners and roll-up displays for events.',
            ],
            [
                'name' => 'Apparel',
                'slug' => 'apparel',
                'icon' => 'shirt',
                'description' => 'Printed t-shirts and hoodies for teams and merchandising.',
            ],
            [
                'name' => 'Packaging',
                'slug' => 'packaging',
                'icon' => 'archive-box',
                'description' => 'Custom packaging boxes, labels and inserts.',
            ],
        ];

        foreach ($catalog as $index => $categoryPayload) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $categoryPayload['slug']],
                [
                    'name' => $categoryPayload['name'],
                    'description' => $categoryPayload['description'] ?? null,
                    'icon' => $categoryPayload['icon'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );

            foreach (($categoryPayload['products'] ?? []) as $productPayload) {
                $product = Product::query()->updateOrCreate(
                    ['slug' => $productPayload['slug']],
                    [
                        'category_id' => $category->id,
                        'name' => $productPayload['name'],
                        'sku' => $productPayload['sku'] ?? Str::upper(Str::random(10)),
                        'short_description' => $productPayload['short_description'] ?? null,
                        'description' => $productPayload['description'] ?? null,
                        'base_price' => $productPayload['base_price'] ?? 0,
                        'min_quantity' => $productPayload['quantities'][0]['quantity'] ?? 100,
                        'base_delivery_days' => $productPayload['quantities'][0]['delivery_days'] ?? 3,
                        'thumbnail' => $productPayload['thumbnail'] ?? null,
                        'is_featured' => $productPayload['is_featured'] ?? false,
                        'is_active' => true,
                    ],
                );

                $product->optionGroups()->delete();
                foreach (($productPayload['options'] ?? []) as $groupIndex => $groupPayload) {
                    $group = $product->optionGroups()->create([
                        'name' => $groupPayload['name'],
                        'code' => $groupPayload['code'],
                        'input_type' => 'select',
                        'is_required' => true,
                        'sort_order' => $groupIndex,
                    ]);

                    foreach (($groupPayload['values'] ?? []) as $valueIndex => $valuePayload) {
                        $group->values()->create([
                            'label' => $valuePayload['label'],
                            'value' => $valuePayload['value'],
                            'price_modifier' => $valuePayload['price_modifier'] ?? 0,
                            'delivery_modifier' => $valuePayload['delivery_modifier'] ?? 0,
                            'is_default' => $valuePayload['is_default'] ?? false,
                            'sort_order' => $valueIndex,
                        ]);
                    }
                }

                $product->quantityPrices()->delete();
                foreach (($productPayload['quantities'] ?? []) as $qtyPayload) {
                    $product->quantityPrices()->create([
                        'quantity' => $qtyPayload['quantity'],
                        'price' => $qtyPayload['price'],
                        'discount_percentage' => $qtyPayload['discount_percentage'] ?? 0,
                        'delivery_days' => $qtyPayload['delivery_days'] ?? null,
                        'is_active' => true,
                    ]);
                }
            }
        }

        Coupon::query()->updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 2000,
                'usage_limit' => 1000,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
            ],
        );
    }
}
