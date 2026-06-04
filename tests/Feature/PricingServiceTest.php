<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFormTemplate;
use App\Models\ProductOptionGroup;
use App\Models\ProductOptionValue;
use App\Models\ProductQuantityPrice;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_base_price_is_used_when_no_calculator_form_is_selected(): void
    {
        $product = $this->createProduct(['base_price' => 8700]);

        $quote = app(PricingService::class)->quote($product, [], 2);

        $this->assertSame(17400.0, $quote['line_total']);
        $this->assertSame(8700.0, $quote['unit_price']);
    }

    public function test_product_base_price_is_not_included_when_calculator_form_is_selected(): void
    {
        $template = $this->createFormTemplate();
        $product = $this->createProduct([
            'base_price' => 4700,
            'product_form_template_id' => $template->id,
        ]);
        $option = $this->createOption($product, ['price_modifier' => 250]);

        $quote = app(PricingService::class)->quote($product, [$option->id], 3);

        $this->assertSame(750.0, $quote['line_total']);
        $this->assertSame(250.0, $quote['unit_price']);
    }

    public function test_calculator_form_uses_only_selected_option_prices_even_when_quantity_prices_exist(): void
    {
        $template = $this->createFormTemplate();
        $product = $this->createProduct([
            'base_price' => 4700,
            'product_form_template_id' => $template->id,
        ]);
        ProductQuantityPrice::query()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'price' => 1000,
            'is_active' => true,
        ]);
        $option = $this->createOption($product, ['price_modifier' => 25]);

        $quote = app(PricingService::class)->quote($product, [$option->id], 100);

        $this->assertSame(2500.0, $quote['line_total']);
        $this->assertSame(25.0, $quote['unit_price']);
        $this->assertNull($quote['quantity_pricing']);
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::query()->create([
            'name' => 'Printing',
            'slug' => 'printing',
        ]);

        return Product::query()->create(array_merge([
            'category_id' => $category->id,
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'base_price' => 0,
            'min_quantity' => 1,
            'base_delivery_days' => 3,
            'is_active' => true,
        ], $overrides));
    }

    private function createFormTemplate(array $overrides = []): ProductFormTemplate
    {
        return ProductFormTemplate::query()->create(array_merge([
            'name' => 'Business Cards Form',
            'slug' => 'business-cards-form',
            'is_active' => true,
        ], $overrides));
    }

    private function createOption(Product $product, array $overrides = []): ProductOptionValue
    {
        $group = ProductOptionGroup::query()->create([
            'product_id' => $product->id,
            'name' => 'Finish',
            'code' => 'finish',
            'input_type' => 'button_tabs',
        ]);

        return ProductOptionValue::query()->create(array_merge([
            'product_option_group_id' => $group->id,
            'label' => 'Gloss',
            'value' => 'gloss',
            'price_modifier' => 0,
        ], $overrides));
    }
}
