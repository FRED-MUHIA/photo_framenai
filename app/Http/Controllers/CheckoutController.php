<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\InvoiceReadyMail;
use App\Mail\NewOrderPlacedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly PaymentService $paymentService,
    ) {
    }

    public function index()
    {
        $items = $this->cartService->all();

        if (empty($items)) {
            return redirect()->route('products.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $totals = $this->cartService->totals();
        $shippingOptions = config('shop.shipping_options');

        return view('checkout.index', compact('items', 'totals', 'shippingOptions'));
    }

    public function store(CheckoutRequest $request)
    {
        $items = $this->cartService->all();

        if (empty($items)) {
            return redirect()->route('products.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validated = $request->validated();

        if (! empty($validated['coupon_code'])) {
            $this->cartService->applyCoupon($validated['coupon_code']);
        }

        $totals = $this->cartService->totals($validated['shipping_option']);
        $deliveryEstimate = collect($items)
            ->pluck('delivery_date')
            ->filter()
            ->max();

        $order = DB::transaction(function () use ($items, $validated, $totals, $deliveryEstimate) {
            $order = Order::query()->create([
                'order_number' => 'NP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => auth()->id(),
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'] ?? null,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'shipping_option' => $validated['shipping_option'],
                'shipping_address_line1' => $validated['shipping_address_line1'],
                'shipping_address_line2' => $validated['shipping_address_line2'] ?? null,
                'shipping_city' => $validated['shipping_city'] ?? '-',
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => $validated['shipping_country'],
                'subtotal' => $totals['subtotal'],
                'shipping_amount' => $totals['shipping_amount'],
                'discount_amount' => $totals['discount_amount'],
                'total_amount' => $totals['total'],
                'currency' => $totals['currency'],
                'delivery_estimate' => $deliveryEstimate ? Carbon::parse($deliveryEstimate) : now()->addDays(4),
                'notes' => $validated['notes'] ?? null,
                'metadata' => [
                    'guest_session_id' => auth()->check() ? null : session()->getId(),
                ],
                'placed_at' => now(),
            ]);

            foreach ($items as $item) {
                $orderItem = OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'artwork_id' => $item['artwork_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku'] ?? null,
                    'configuration' => [
                        'selected_options' => $item['selected_options'] ?? [],
                        'selected_values' => $item['selected_values'] ?? [],
                        'custom_fields' => $item['custom_fields'] ?? [],
                        'delivery_date' => $item['delivery_date'] ?? null,
                        'rush_order' => (bool) ($item['rush_order'] ?? false),
                        'rush_fee' => (float) ($item['rush_fee'] ?? 0),
                    ],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                    'delivery_days' => $item['delivery_days'] ?? null,
                ]);

                if (! empty($item['artwork_id'])) {
                    $orderItem->artwork?->update(['order_item_id' => $orderItem->id]);
                }
            }

            $coupon = $totals['coupon'] ?? null;
            if ($coupon && $coupon->isCurrentlyValid((float) $totals['subtotal'])) {
                $coupon->increment('used_count');
            }

            $paymentPayload = $this->paymentService->initiate($validated['payment_method'], (float) $totals['total'], [
                'order_number' => $order->order_number,
                'customer_email' => $order->customer_email,
            ]);

            $order->update([
                'metadata' => [
                    ...($order->metadata ?? []),
                    'payment' => $paymentPayload,
                    'coupon_code' => $coupon?->code,
                ],
            ]);

            if (auth()->check() && ! auth()->user()->phone && ! empty($validated['phone'])) {
                auth()->user()->update(['phone' => $validated['phone']]);
            }

            return $order;
        });

        $this->sendOrderPlacedEmails($order->fresh(['items']));

        $this->cartService->clear();

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = Order::query()->with('items')->where('order_number', $orderNumber)->firstOrFail();

        if (auth()->check() && auth()->id() !== $order->user_id && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        if (! auth()->check() && data_get($order->metadata, 'guest_session_id') !== session()->getId()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    private function sendOrderPlacedEmails(Order $order): void
    {
        try {
            Mail::to($order->customer_email)->send(new InvoiceReadyMail($order));
        } catch (Throwable $exception) {
            Log::error('Failed to send invoice ready email.', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $exception->getMessage(),
            ]);
        }

        $storeEmail = $this->storeNotificationEmail();

        if (! $storeEmail || strtolower($storeEmail) === strtolower((string) $order->customer_email)) {
            return;
        }

        try {
            Mail::to($storeEmail)->send(new NewOrderPlacedMail($order));
        } catch (Throwable $exception) {
            Log::error('Failed to send new order notification email.', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'recipient' => $storeEmail,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function storeNotificationEmail(): ?string
    {
        return config('shop.notification_email');
    }
}
