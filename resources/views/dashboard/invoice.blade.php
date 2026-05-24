@extends('layouts.storefront')

@section('meta_title', 'Invoice ' . $order->order_number)

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-8">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="np-type-section-title">Invoice</h1>
                <p class="mt-1 text-sm text-slate-500">Order: {{ $order->order_number }}</p>
            </div>
            <button onclick="window.print()" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold">Print</button>
        </div>

        <div class="mt-6 grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-wider text-slate-400">Billed To</p>
                <p class="font-semibold">{{ $order->customer_name }}</p>
                <p class="text-sm text-slate-600">{{ $order->customer_email }}</p>
                <p class="text-sm text-slate-600">{{ $order->customer_phone }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider text-slate-400">Shipping Address</p>
                <p class="text-sm text-slate-700">{{ $order->shipping_address_line1 }}</p>
                @if($order->shipping_address_line2)<p class="text-sm text-slate-700">{{ $order->shipping_address_line2 }}</p>@endif
                <p class="text-sm text-slate-700">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
            </div>
        </div>

        <table class="mt-8 w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                    <th class="py-2">Item</th>
                    <th class="py-2">Qty</th>
                    <th class="py-2">Unit Price</th>
                    <th class="py-2">Delivery Date</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $item->product_name }}</td>
                        <td class="py-2">{{ $item->quantity }}</td>
                        <td class="py-2">{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-2">
                            @if(data_get($item->configuration, 'delivery_date'))
                                {{ \Illuminate\Support\Carbon::parse(data_get($item->configuration, 'delivery_date'))->toFormattedDateString() }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-2 text-right">{{ $order->currency }} {{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 ml-auto max-w-xs space-y-1 text-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>Shipping</span><span>{{ $order->currency }} {{ number_format($order->shipping_amount, 2) }}</span></div>
            <div class="flex justify-between"><span>Discount</span><span>-{{ $order->currency }} {{ number_format($order->discount_amount, 2) }}</span></div>
            <div class="flex justify-between text-base font-bold"><span>Total</span><span>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</span></div>
        </div>
    </div>
</section>
@endsection
