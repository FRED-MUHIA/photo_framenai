<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessArtworkUpload;
use App\Models\Artwork;
use App\Models\Product;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'custom_fields' => ['nullable', 'array'],
            'custom_fields.*' => ['nullable', 'string', 'max:500'],
            'shipping_option' => ['nullable', 'in:not_necessary,standard,express,pickup'],
            'rush_order' => ['nullable', 'boolean'],
            'artwork_id' => ['nullable', 'exists:artworks,id'],
            'delivery_date' => ['nullable', 'date_format:Y-m-d'],
            'artwork' => [
                'nullable',
                'file',
                'mimetypes:' . implode(',', config('shop.allowed_artwork_mimes', [])),
                'max:' . config('shop.max_artwork_size_kb', 20480),
            ],
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
        $customFields = $this->collectCustomFields($product, $validated['custom_fields'] ?? []);

        $shippingOption = $validated['shipping_option'] ?? 'standard';
        $requestedDeliveryDate = $validated['delivery_date'] ?? null;
        $standardQuote = $this->pricingService->quote($product, $selections, $quantity, $shippingOption);
        $rushOrder = $this->shouldApplyRushOrder($requestedDeliveryDate, (int) $standardQuote['standard_delivery_days'], $request->boolean('rush_order'));
        $quote = $this->pricingService->quote($product, $selections, $quantity, $shippingOption, $rushOrder);
        $deliveryDate = $this->resolveDeliveryDate($requestedDeliveryDate, (int) $quote['delivery_days']);

        $artwork = null;
        if ($request->hasFile('artwork')) {
            $file = $request->file('artwork');
            $storedPath = $file->store('artworks/' . now()->format('Y/m'), 'public');

            $artwork = Artwork::query()->create([
                'user_id' => auth()->id(),
                'guest_token' => auth()->check() ? null : $request->session()->getId(),
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath,
                'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                'extension' => Str::lower($file->getClientOriginalExtension()),
                'size_bytes' => $file->getSize(),
                'status' => 'pending',
            ]);

            ProcessArtworkUpload::dispatch($artwork->id);
        } elseif (! empty($validated['artwork_id'])) {
            $artwork = Artwork::query()
                ->whereKey($validated['artwork_id'])
                ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
                ->when(! auth()->check(), fn ($query) => $query->where('guest_token', $request->session()->getId()))
                ->first();

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
            'custom_fields' => $customFields,
            'unit_price' => $quote['unit_price'],
            'line_total' => $quote['line_total'],
            'delivery_days' => $quote['delivery_days'],
            'delivery_date' => $deliveryDate,
            'rush_order' => $rushOrder,
            'rush_fee' => $quote['rush_fee'],
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
            'delivery_date' => ['nullable', 'date_format:Y-m-d'],
            'rush_order' => ['nullable', 'boolean'],
        ]);

        $items = $this->cartService->all();
        $item = $items[$index] ?? null;

        if (! $item) {
            return back()->withErrors(['cart' => 'Cart item not found.']);
        }

        if (! empty($item['frame_customization'])) {
            $quantity = (int) $validated['quantity'];
            $configuration = $item['frame_customization'];
            $unitPrice = (float) data_get($configuration, 'price_breakdown.unit_price', $item['unit_price']);
            $lineTotal = round($unitPrice * $quantity, 2);
            data_set($configuration, 'price_breakdown.quantity', $quantity);
            data_set($configuration, 'price_breakdown.total', $lineTotal);
            $requested = $validated['delivery_date'] ?? ($item['delivery_date'] ?? null);
            $deliveryDate = $this->resolveDeliveryDate($requested, (int) ($item['delivery_days'] ?? 7));
            $this->cartService->update($index, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'delivery_date' => $deliveryDate,
                'frame_customization' => $configuration,
            ]);

            return back()->with('status', 'Custom frame cart item updated.');
        }

        $product = Product::query()->active()->with(['optionGroups.values', 'quantityPrices'])->findOrFail($item['product_id']);
        $quantity = (int) $validated['quantity'];
        $minQuantity = max(1, (int) $product->min_quantity);

        if ($quantity < $minQuantity) {
            throw ValidationException::withMessages([
                'quantity' => 'The minimum quantity for this product is ' . number_format($minQuantity) . '.',
            ]);
        }

        $requestedDeliveryDate = $validated['delivery_date'] ?? ($item['delivery_date'] ?? null);
        $shippingOption = $item['shipping_option'] ?? 'standard';
        $standardQuote = $this->pricingService->quote($product, $item['selected_options'] ?? [], $quantity, $shippingOption);
        $rushOrder = $this->shouldApplyRushOrder(
            $requestedDeliveryDate,
            (int) $standardQuote['standard_delivery_days'],
            $request->has('rush_order') ? $request->boolean('rush_order') : (bool) ($item['rush_order'] ?? false)
        );
        $quote = $this->pricingService->quote($product, $item['selected_options'] ?? [], $quantity, $shippingOption, $rushOrder);
        $deliveryDate = $this->resolveDeliveryDate(
            $requestedDeliveryDate,
            (int) $quote['delivery_days']
        );

        $this->cartService->update($index, [
            'quantity' => $quantity,
            'min_quantity' => $minQuantity,
            'unit_price' => $quote['unit_price'],
            'line_total' => $quote['line_total'],
            'delivery_days' => $quote['delivery_days'],
            'delivery_date' => $deliveryDate,
            'rush_order' => $rushOrder,
            'rush_fee' => $quote['rush_fee'],
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

    private function resolveDeliveryDate(?string $requestedDate, int $deliveryDays): string
    {
        $defaultDate = today()->addDays(max(0, $deliveryDays))->toDateString();

        if (! $requestedDate) {
            return $defaultDate;
        }

        $today = today()->toDateString();

        if (Carbon::parse($requestedDate)->lt(Carbon::parse($today))) {
            return $today;
        }

        return $requestedDate;
    }

    private function shouldApplyRushOrder(?string $requestedDate, int $standardDeliveryDays, bool $requestedRushOrder): bool
    {
        if ($requestedRushOrder) {
            return true;
        }

        if (! $requestedDate) {
            return false;
        }

        return Carbon::parse($requestedDate)->isSameDay(today());
    }

    private function collectCustomFields(Product $product, array $input): array
    {
        $fields = [];

        foreach ($product->optionGroups->where('input_type', 'text') as $group) {
            $value = trim((string) data_get($input, $group->code, ''));

            if ($group->is_required && $value === '') {
                throw ValidationException::withMessages([
                    'custom_fields.' . $group->code => $group->name . ' is required.',
                ]);
            }

            if ($value === '') {
                continue;
            }

            $fields[] = [
                'group_code' => $group->code,
                'label' => $group->name,
                'value' => $value,
            ];
        }

        return $fields;
    }
}
