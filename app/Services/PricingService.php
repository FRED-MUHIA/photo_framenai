<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\ProductQuantityPrice;
use Illuminate\Support\Collection;

class PricingService
{
    public function quote(Product $product, array $selectedOptionIds, int $quantity, string $shippingOption = 'standard', bool $rushOrder = false): array
    {
        $quantity = max(1, $quantity);

        $product->loadMissing([
            'optionGroups.values',
            'quantityPrices',
        ]);

        $selectedValues = ProductOptionValue::query()
            ->whereIn('id', array_values($selectedOptionIds))
            ->whereIn('product_option_group_id', $product->optionGroups->pluck('id'))
            ->get()
            ->keyBy('id');

        $usesCalculatorForm = ! empty($product->product_form_template_id);
        $quantityPrice = $usesCalculatorForm
            ? null
            : $this->resolveQuantityPrice($product->quantityPrices, $quantity);

        $baseTotal = $quantityPrice
            ? $this->calculateTieredBaseTotal($quantityPrice, $quantity)
            : ($usesCalculatorForm ? 0.0 : (float) $product->base_price * $quantity);

        $optionModifierPerUnit = (float) $selectedValues->sum(fn (ProductOptionValue $value) => (float) $value->price_modifier);
        $optionTotal = $optionModifierPerUnit * $quantity;

        $productSubtotal = round($baseTotal + $optionTotal, 2);
        $rushFee = $rushOrder ? (float) config('shop.rush_order_fee', 500) : 0.0;
        $subtotal = round($productSubtotal + $rushFee, 2);
        $unitPrice = round($productSubtotal / max($quantity, 1), 2);

        $optionDeliveryModifier = (int) $selectedValues->sum('delivery_modifier');
        $deliveryDays = (int) (($quantityPrice?->delivery_days ?? $product->base_delivery_days) + $optionDeliveryModifier);

        $shippingDays = (int) data_get(config('shop.shipping_options'), $shippingOption . '.days', 3);
        $standardDeliveryDays = max(1, $deliveryDays + $shippingDays);
        $deliveryDays = $rushOrder ? 0 : $standardDeliveryDays;

        return [
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $subtotal,
            'delivery_days' => $deliveryDays,
            'standard_delivery_days' => $standardDeliveryDays,
            'rush_order' => $rushOrder,
            'rush_fee' => $rushFee,
            'selected_values' => $selectedValues->values()->map(fn (ProductOptionValue $value) => [
                'id' => $value->id,
                'label' => $value->label,
                'value' => $value->value,
                'group_code' => optional($product->optionGroups->firstWhere('id', $value->product_option_group_id))->code,
                'price_modifier' => (float) $value->price_modifier,
            ])->all(),
            'quantity_pricing' => $quantityPrice ? [
                'quantity' => $quantityPrice->quantity,
                'price' => (float) $quantityPrice->price,
                'discount_percentage' => (float) $quantityPrice->discount_percentage,
            ] : null,
            'currency' => config('shop.currency', 'KES'),
        ];
    }

    private function resolveQuantityPrice(Collection $quantityPrices, int $quantity): ?ProductQuantityPrice
    {
        if ($quantityPrices->isEmpty()) {
            return null;
        }

        $sortedPrices = $quantityPrices->sortBy('quantity')->values();

        $exact = $sortedPrices->firstWhere('quantity', $quantity);
        if ($exact) {
            return $exact;
        }

        $lowerTier = $sortedPrices
            ->filter(fn (ProductQuantityPrice $row) => $row->quantity <= $quantity)
            ->last();

        return $lowerTier ?: $sortedPrices->first();
    }

    private function calculateTieredBaseTotal(ProductQuantityPrice $quantityPrice, int $quantity): float
    {
        if ((int) $quantityPrice->quantity === $quantity) {
            return (float) $quantityPrice->price;
        }

        $tierUnitPrice = (float) $quantityPrice->price / max(1, (int) $quantityPrice->quantity);

        return round($tierUnitPrice * $quantity, 2);
    }
}
