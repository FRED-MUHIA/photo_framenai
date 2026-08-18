<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuoteController extends Controller
{
    public function __construct(private readonly PricingService $pricingService)
    {
    }

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'selected_options' => ['nullable', 'array'],
            'selected_options.*' => ['nullable', 'integer'],
            'shipping_option' => ['nullable', 'in:not_necessary,standard,express,pickup'],
            'rush_order' => ['nullable', 'boolean'],
        ]);

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($validated['product_id']);
        $quantity = (int) $validated['quantity'];
        $minQuantity = max(1, (int) $product->min_quantity);

        if ($quantity < $minQuantity) {
            throw ValidationException::withMessages([
                'quantity' => 'The minimum quantity for this product is ' . number_format($minQuantity) . '.',
            ]);
        }

        $quote = $this->pricingService->quote(
            $product,
            array_filter($validated['selected_options'] ?? []),
            $quantity,
            $validated['shipping_option'] ?? 'standard',
            $request->boolean('rush_order'),
        );

        return response()->json([
            'message' => 'Quote calculated successfully.',
            'quote' => $quote,
        ]);
    }
}
