<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Product;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly PricingService $pricingService,
    ) {
    }

    public function index()
    {
        $items = $this->cartService->all();
        $totals = $this->cartService->totals();

        return view('cart.index', compact('items', 'totals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'selected_options' => ['nullable', 'array'],
            'selected_options.*' => ['nullable', 'integer'],
            'artwork_id' => ['nullable', 'exists:artworks,id'],
        ]);

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($validated['product_id']);
        $selections = array_filter($validated['selected_options'] ?? []);

        $quote = $this->pricingService->quote($product, $selections, (int) $validated['quantity']);

        $artwork = null;
        if (! empty($validated['artwork_id'])) {
            $artwork = Artwork::query()->find($validated['artwork_id']);
            if (! $artwork) {
                throw ValidationException::withMessages(['artwork_id' => 'Artwork not found.']);
            }
        }

        $this->cartService->add([
            'line_key' => (string) Str::uuid(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'sku' => $product->sku,
            'thumbnail' => $product->thumbnail,
            'quantity' => (int) $validated['quantity'],
            'selected_options' => $selections,
            'selected_values' => $quote['selected_values'],
            'unit_price' => $quote['unit_price'],
            'line_total' => $quote['line_total'],
            'delivery_days' => $quote['delivery_days'],
            'artwork_id' => $artwork?->id,
            'artwork_name' => $artwork?->original_name,
        ]);

        return redirect()->route('cart.index')->with('status', 'Product added to cart successfully.');
    }

    public function update(Request $request, int $index)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = $this->cartService->all();
        $item = $items[$index] ?? null;

        if (! $item) {
            return back()->withErrors(['cart' => 'Cart item not found.']);
        }

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($item['product_id']);
        $quote = $this->pricingService->quote($product, $item['selected_options'] ?? [], (int) $validated['quantity']);

        $this->cartService->update($index, [
            'quantity' => (int) $validated['quantity'],
            'unit_price' => $quote['unit_price'],
            'line_total' => $quote['line_total'],
            'delivery_days' => $quote['delivery_days'],
        ]);

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(int $index)
    {
        $this->cartService->remove($index);

        return back()->with('status', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:40'],
        ]);

        $coupon = $this->cartService->applyCoupon($validated['coupon_code'] ?? null);

        if (! $coupon) {
            return back()->withErrors(['coupon_code' => 'Invalid coupon code.']);
        }

        return back()->with('status', 'Coupon applied.');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('products.index')->with('status', 'Cart cleared.');
    }
}
