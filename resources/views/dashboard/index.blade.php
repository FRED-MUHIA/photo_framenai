@extends('layouts.storefront')

@section('meta_title', 'Customer Dashboard - NaiPrinters')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="np-type-page-title">Customer Dashboard</h1>
            <p class="mt-1 text-sm text-slate-600">Track orders, reorder quickly, and manage saved artworks.</p>
        </div>
        <a href="{{ route('products.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">New Order</a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
        <div>
            <h2 class="text-xl font-extrabold">Order History</h2>
            <div class="mt-4 space-y-3">
                @forelse($orders as $order)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $order->order_number }}</p>
                                <h3 class="text-lg font-bold">{{ ucfirst($order->status) }}</h3>
                                <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y') }} | {{ $order->items->count() }} items</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-extrabold">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
                                <p class="text-xs text-slate-500">Payment: {{ ucfirst($order->payment_status) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('dashboard.orders.reorder', $order) }}">
                                @csrf
                                <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold">Reorder</button>
                            </form>
                            <a href="{{ route('dashboard.orders.invoice', $order) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold">Download Invoice</a>
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">No orders yet.</p>
                @endforelse
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        </div>

        <aside>
            <h2 class="text-xl font-extrabold">Saved Designs</h2>
            <div class="mt-4 space-y-3">
                @forelse($artworks as $artwork)
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-sm font-bold">{{ $artwork->original_name }}</p>
                        <p class="text-xs text-slate-500">{{ strtoupper($artwork->extension ?? '-') }} | {{ ucfirst($artwork->status) }}</p>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 bg-white p-5 text-sm text-slate-500">No saved designs yet.</p>
                @endforelse
                <a href="{{ route('artwork.create') }}" class="inline-flex rounded-lg bg-teal-600 px-3 py-2 text-xs font-bold text-white">Upload New Design</a>
            </div>
        </aside>
    </div>
</section>
@endsection
