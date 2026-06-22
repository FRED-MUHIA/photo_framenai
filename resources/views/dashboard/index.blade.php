@extends('layouts.storefront')

@section('meta_title', 'Customer Dashboard - Nai Printers')

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
                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <h4 class="text-sm font-extrabold">Order details</h4>
                            <div class="mt-3 divide-y divide-slate-100 rounded-xl border border-slate-200">
                                @foreach($order->items as $item)
                                    <div class="p-3">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $item->product_name }}</p>
                                                <p class="text-xs text-slate-500">Quantity: {{ $item->quantity }} @if($item->sku)| SKU: {{ $item->sku }}@endif</p>
                                            </div>
                                            <p class="text-sm font-extrabold">{{ $order->currency }} {{ number_format((float) $item->line_total, 2) }}</p>
                                        </div>

                                        @php
                                            $selectedValues = collect(data_get($item->configuration, 'selected_values', []));
                                            $customFields = collect(data_get($item->configuration, 'custom_fields', []));
                                        @endphp

                                        @if($selectedValues->isNotEmpty() || $customFields->isNotEmpty())
                                            <div class="mt-2 grid gap-1 text-xs text-slate-600 sm:grid-cols-2">
                                                @foreach($selectedValues as $value)
                                                    <p>{{ strtoupper($value['group_code'] ?? 'Option') }}: {{ $value['label'] ?? '-' }}</p>
                                                @endforeach
                                                @foreach($customFields as $field)
                                                    <p>{{ $field['label'] ?? 'Custom field' }}: {{ $field['value'] ?? '-' }}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 grid gap-3 text-sm text-slate-600 md:grid-cols-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Delivery</p>
                                    <p>{{ optional($order->delivery_estimate)->format('d M Y') ?? 'To be confirmed' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Shipping</p>
                                    <p>{{ str($order->shipping_option)->replace('_', ' ')->title() }}</p>
                                    <p>{{ $order->shipping_address_line1 }}, {{ $order->shipping_country }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Payment</p>
                                    <p>{{ strtoupper($order->payment_method) }} | {{ ucfirst($order->payment_status) }}</p>
                                    <p>Subtotal: {{ $order->currency }} {{ number_format((float) $order->subtotal, 2) }}</p>
                                </div>
                            </div>

                            @if($order->notes)
                                <p class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600"><span class="font-bold text-slate-800">Notes:</span> {{ $order->notes }}</p>
                            @endif
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

            <div class="mt-8">
                <h2 class="text-xl font-extrabold">Account</h2>
                <div class="mt-4 space-y-4 rounded-xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-bold">Log out</button>
                    </form>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="border-t border-slate-100 pt-4">
                        @csrf
                        @method('delete')

                        <label for="dashboard_delete_password" class="text-xs font-bold uppercase tracking-wide text-slate-500">Delete account</label>
                        <input
                            id="dashboard_delete_password"
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="mt-2 w-full rounded-lg border-slate-300 text-sm"
                            autocomplete="current-password"
                        >
                        @error('password', 'userDeletion')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <button class="mt-3 w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-bold text-white" onclick="return confirm('Delete your account permanently?')">Delete account</button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
