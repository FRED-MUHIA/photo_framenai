@extends('layouts.storefront')

@section('meta_title', 'Checkout - NaiPrinters')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">Checkout</h1>
    <p class="mt-2 text-sm text-slate-600">Complete your order in a few steps.</p>

    <form
        method="POST"
        action="{{ route('checkout.store') }}"
        class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]"
        x-data="checkoutSummary(@js($shippingOptions), @js((float) $totals['subtotal']), @js((float) $totals['discount_amount']), @js(old('shipping_option', 'standard')))"
    >
        @csrf
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-bold">Contact Details</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Full Name" class="rounded-xl border-slate-300" required>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Email Address" class="rounded-xl border-slate-300" required>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="Phone Number" class="rounded-xl border-slate-300 sm:col-span-2">
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-bold">Shipping Details</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <input type="text" name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" placeholder="Address line 1" class="rounded-xl border-slate-300 sm:col-span-2" required>
                    <input type="text" name="shipping_address_line2" value="{{ old('shipping_address_line2') }}" placeholder="Address line 2" class="rounded-xl border-slate-300 sm:col-span-2">
                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" placeholder="City" class="rounded-xl border-slate-300" required>
                    <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" placeholder="State / County" class="rounded-xl border-slate-300">
                    <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="Postal Code" class="rounded-xl border-slate-300">
                    <input type="text" name="shipping_country" value="{{ old('shipping_country', 'Kenya') }}" placeholder="Country" class="rounded-xl border-slate-300" required>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm font-semibold">Shipping Option</label>
                    <select name="shipping_option" x-model="selectedShipping" class="w-full rounded-xl border-slate-300">
                        @foreach($shippingOptions as $key => $option)
                            <option value="{{ $key }}" @selected(old('shipping_option', 'standard') === $key)>{{ $option['label'] }} - {{ config('shop.currency_symbol') }} {{ number_format($option['price'], 2) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-bold">Payment Method</h2>
                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3"><input type="radio" name="payment_method" value="stripe" @checked(old('payment_method') === 'stripe')> Stripe</label>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3"><input type="radio" name="payment_method" value="paypal" @checked(old('payment_method') === 'paypal')> PayPal</label>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3"><input type="radio" name="payment_method" value="mpesa" @checked(old('payment_method', 'mpesa') === 'mpesa')> M-Pesa</label>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3"><input type="radio" name="payment_method" value="cod" @checked(old('payment_method') === 'cod')> Pay on Delivery</label>
                </div>
                <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Coupon code (optional)" class="mt-4 w-full rounded-xl border-slate-300">
                <textarea name="notes" rows="3" placeholder="Order notes" class="mt-4 w-full rounded-xl border-slate-300">{{ old('notes') }}</textarea>
            </div>
        </div>

        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-extrabold">Order Summary</h3>
            <div class="mt-4 space-y-2 text-sm">
                @foreach($items as $item)
                    <div class="flex justify-between gap-3 border-b border-slate-100 pb-2">
                        <span>{{ $item['product_name'] }} x {{ $item['quantity'] }}</span>
                        <span>{{ config('shop.currency_symbol') }} {{ number_format($item['line_total'], 2) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between"><span>Subtotal</span><span>{{ config('shop.currency_symbol') }} {{ number_format($totals['subtotal'], 2) }}</span></div>
                <div class="flex justify-between"><span>Shipping</span><span>{{ config('shop.currency_symbol') }} <span x-text="formatMoney(shippingAmount())"></span></span></div>
                <div class="flex justify-between text-emerald-700"><span>Discount</span><span>-{{ config('shop.currency_symbol') }} {{ number_format($totals['discount_amount'], 2) }}</span></div>
                <hr>
                <div class="flex justify-between text-lg font-extrabold"><span>Total</span><span>{{ config('shop.currency_symbol') }} <span x-text="formatMoney(totalAmount())"></span></span></div>
            </div>
            <button class="mt-5 w-full rounded-xl bg-slate-900 py-2.5 text-sm font-bold text-white">Place Order</button>
        </aside>
    </form>
</section>
@endsection

@push('scripts')
<script>
    function checkoutSummary(shippingOptions, subtotal, discountAmount, selectedShipping) {
        return {
            shippingOptions,
            subtotal: Number(subtotal || 0),
            discountAmount: Number(discountAmount || 0),
            selectedShipping,
            shippingAmount() {
                return Number(this.shippingOptions[this.selectedShipping]?.price || 0);
            },
            totalAmount() {
                return Math.max(0, this.subtotal + this.shippingAmount() - this.discountAmount);
            },
            formatMoney(value) {
                const number = Number(value || 0);
                return number.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
        };
    }
</script>
@endpush
