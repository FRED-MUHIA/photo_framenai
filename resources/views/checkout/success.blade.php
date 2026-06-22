@extends('layouts.storefront')

@section('meta_title', 'Order Confirmation - Nai Printers')
@section('meta_robots', 'noindex, nofollow, noarchive')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-8 text-center">
        <h1 class="np-type-page-title text-emerald-800">Order Confirmed</h1>
        <p class="mt-3 text-sm text-emerald-700">Thank you for your order. Your order number is <span class="font-extrabold">{{ $order->order_number }}</span>.</p>
        <div class="mx-auto mt-6 max-w-md rounded-2xl bg-white p-5 text-left">
            <p class="text-sm"><span class="font-semibold">Status:</span> {{ ucfirst($order->status) }}</p>
            <p class="text-sm"><span class="font-semibold">Payment:</span> {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})</p>
            <p class="text-sm"><span class="font-semibold">Total:</span> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p class="text-sm"><span class="font-semibold">Delivery Estimate:</span> {{ optional($order->delivery_estimate)->toFormattedDateString() }}</p>
        </div>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="rounded-xl bg-slate-900 px-5 py-2 text-sm font-bold text-white">Go to Dashboard</a>
            <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-bold">Continue Shopping</a>
        </div>
    </div>
</section>
@endsection
