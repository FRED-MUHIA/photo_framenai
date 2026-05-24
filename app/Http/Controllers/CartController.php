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
            'shipping_option' => ['nullable', 'in:standard,express,pickup'],
            'artwork_id' => ['nullable', 'exists:artworks,id'],
        ]);

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($validated['product_id']);
        $quantity = (int) $validated['quantity'];
        $minQuantity = max(1, (int) $product->min_quantity);

        if ($quantity < $minQuantity) {
            throw ValidationException::withMessages([
                'quantity' => 'The minimum quantity for this product is ' . number_format($minQuantity) . '.',
            ]);
        }

        $selections = array_filter($validated['selected_options'] ?? []);

        $shippingOption = $validated['shipping_option'] ?? 'standard';
        $quote = $this->pricingService->quote($product, $selections, $quantity, $shippingOption);

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
            'quantity' => $quantity,
            'min_quantity' => $minQuantity,
            'selected_options' => $selections,
            'selected_values' => $quote['selected_values'],
            'unit_price' => $quote['unit_price'],
            'line_total' => $quote['line_total'],
            'delivery_days' => $quote['delivery_days'],
            'shipping_option' => $shippingOption,
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
        $quantity = (int) $validated['quantity'];
        $minQuantity = max(1, (int) $product->min_quantity);

        if ($quantity < $minQuantity) {
            throw ValidationException::withMessages([
                'quantity' => 'The minimum quantity for this product is ' . number_format($minQuantity) . '.',
            ]);
        }

        $quote = $this->pricingService->quote($product, $item['selected_options'] ?? [], $quantity);

        $this->cartService->update($index, [
            'quantity' => $quantity,
            'min_quantity' => $minQuantity,
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
