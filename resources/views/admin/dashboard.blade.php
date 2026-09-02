@extends('layouts.admin')

@section('heading', 'Admin Overview')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs uppercase text-slate-500">Orders</p><p class="mt-1 text-2xl font-extrabold">{{ number_format($stats['orders']) }}</p></div>
    <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs uppercase text-slate-500">Revenue</p><p class="mt-1 text-2xl font-extrabold">{{ config('shop.currency_symbol') }} {{ number_format($stats['revenue'], 2) }}</p></div>
    <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs uppercase text-slate-500">Customers</p><p class="mt-1 text-2xl font-extrabold">{{ number_format($stats['customers']) }}</p></div>
    <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs uppercase text-slate-500">Products</p><p class="mt-1 text-2xl font-extrabold">{{ number_format($stats['products']) }}</p></div>
    <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs uppercase text-slate-500">Pending Artworks</p><p class="mt-1 text-2xl font-extrabold">{{ number_format($stats['pending_artworks']) }}</p></div>
</div>

<div class="mt-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="text-lg font-bold">Recent Orders</h2>
    <div class="mt-3 overflow-x-auto">
        <table class="min-w-[40rem] w-full text-left text-sm">
            <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Order</th><th>Status</th><th>Total</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold text-slate-700">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-3 text-slate-500">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
