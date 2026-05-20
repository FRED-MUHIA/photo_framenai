<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $orders = Order::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.artwork', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,printing,shipped,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,failed,refunded'],
        ]);

        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
        ]);

        return back()->with('status', 'Order status updated.');
    }
}
