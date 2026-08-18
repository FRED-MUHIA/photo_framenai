@extends('layouts.storefront')

@section('meta_title', 'Your Cart - ' . config('app.name'))
@section('meta_robots', 'noindex, nofollow, noarchive')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">Shopping Cart</h1>

    @if(empty($items))
        <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <p class="text-slate-600">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Browse products</a>
        </div>
    @else
        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_330px]">
            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold">{{ $item['product_name'] }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ config('shop.currency_symbol') }} {{ number_format($item['unit_price'], 2) }} each</p>
                                <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-500">
                                    @foreach(($item['selected_values'] ?? []) as $value)
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ strtoupper($value['group_code']) }}: {{ $value['label'] }}</span>
                                    @endforeach
                                    @foreach(($item['custom_fields'] ?? []) as $field)
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ $field['label'] }}: {{ $field['value'] }}</span>
                                    @endforeach
                                </div>
                                @if(!empty($item['artwork_name']))
                                    <p class="mt-2 text-xs font-semibold text-teal-700">Artwork: {{ $item['artwork_name'] }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-extrabold">{{ config('shop.currency_symbol') }} {{ number_format($item['line_total'], 2) }}</p>
                                <p class="text-xs text-slate-500">Delivery ~ {{ $item['delivery_days'] }} days</p>
                                @if(!empty($item['delivery_date']))
                                    <p class="mt-1 text-xs font-semibold text-emerald-700">Requested: {{ \Illuminate\Support\Carbon::parse($item['delivery_date'])->toFormattedDateString() }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <form method="POST" action="{{ route('cart.update', $index) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                @php
                                    $minimumDeliveryDate = now()->addDays(max(1, (int) ($item['delivery_days'] ?? 1)))->toDateString();
                                    $selectedDeliveryDate = $item['delivery_date'] ?? $minimumDeliveryDate;
                                @endphp
                                <input
                                    type="number"
                                    name="quantity"
                                    min="{{ data_get($item, 'min_quantity', 1) }}"
                                    step="1"
                                    value="{{ $item['quantity'] }}"
                                    class="w-28 rounded-lg border-slate-300 text-sm"
                                    required
                                >
                                <input
                                    type="date"
                                    name="delivery_date"
                                    min="{{ $minimumDeliveryDate }}"
                                    value="{{ $selectedDeliveryDate }}"
                                    class="w-40 rounded-lg border-slate-300 text-sm"
                                >
                                <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold">Update</button>
                            </form>
                            <form method="POST" action="{{ route('cart.destroy', $index) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-extrabold">Order Summary</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ config('shop.currency_symbol') }} {{ number_format($totals['subtotal'], 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>{{ config('shop.currency_symbol') }} {{ number_format($totals['shipping_amount'], 2) }}</span></div>
                    <div class="flex justify-between text-emerald-700"><span>Discount</span><span>-{{ config('shop.currency_symbol') }} {{ number_format($totals['discount_amount'], 2) }}</span></div>
                    <hr>
                    <div class="flex justify-between text-lg font-extrabold"><span>Total</span><span>{{ config('shop.currency_symbol') }} {{ number_format($totals['total'], 2) }}</span></div>
                </div>

                <form method="POST" action="{{ route('cart.coupon') }}" class="mt-4 flex gap-2">
                    @csrf
                    <input type="text" name="coupon_code" placeholder="Coupon code" class="w-full rounded-lg border-slate-300 text-sm">
                    <button class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white">Apply</button>
                </form>

                <a href="{{ route('checkout.index') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-teal-600 py-2.5 text-sm font-bold text-white">Proceed to Checkout</a>

                <form method="POST" action="{{ route('cart.clear') }}" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button class="w-full rounded-xl border border-slate-300 py-2 text-xs font-semibold">Clear Cart</button>
                </form>
            </aside>
        </div>
    @endif
</section>
@endsection
