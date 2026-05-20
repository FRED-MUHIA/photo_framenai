<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;

class CustomerDashboardController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->paginate(8);

        $artworks = $user->artworks()->latest()->take(8)->get();

        return view('dashboard.index', compact('orders', 'artworks'));
    }

    public function reorder(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        foreach ($order->items as $item) {
            $this->cartService->add([
                'line_key' => (string) \Illuminate\Support\Str::uuid(),
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'product_slug' => $item->product?->slug,
                'sku' => $item->sku,
                'thumbnail' => $item->product?->thumbnail,
                'quantity' => $item->quantity,
                'selected_options' => data_get($item->configuration, 'selected_options', []),
                'selected_values' => data_get($item->configuration, 'selected_values', []),
                'unit_price' => (float) $item->unit_price,
                'line_total' => (float) $item->line_total,
                'delivery_days' => $item->delivery_days,
                'artwork_id' => $item->artwork_id,
                'artwork_name' => $item->artwork?->original_name,
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Items were added to cart for reorder.');
    }

    public function invoice(Order $order)
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()->isAdmin(), 403);

        $order->load('items');

        return view('dashboard.invoice', compact('order'));
    }
}
