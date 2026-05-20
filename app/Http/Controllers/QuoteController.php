<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;

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
            'shipping_option' => ['nullable', 'in:standard,express,pickup'],
        ]);

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($validated['product_id']);

        $quote = $this->pricingService->quote(
            $product,
            array_filter($validated['selected_options'] ?? []),
            (int) $validated['quantity'],
            $validated['shipping_option'] ?? 'standard',
        );

        return response()->json([
            'message' => 'Quote calculated successfully.',
            'quote' => $quote,
        ]);
    }
}
