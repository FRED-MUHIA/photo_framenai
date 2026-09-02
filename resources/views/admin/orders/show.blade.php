@extends('layouts.admin')

@section('heading', 'Order Details')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">{{ $order->order_number }}</h2>
        <p class="text-sm text-slate-500">{{ $order->customer_name }} | {{ $order->customer_email }}</p>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-[48rem] w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Product</th><th>Qty</th><th>Unit</th><th>Total</th><th>Delivery Date</th><th>Artwork</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">
                                {{ $item->product_name }}
                                @if(data_get($item->configuration, 'rush_order'))
                                    <div class="text-xs font-extrabold text-rose-700">Rush same-day: {{ $order->currency }} {{ number_format((float) data_get($item->configuration, 'rush_fee', config('shop.rush_order_fee', 500)), 2) }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $order->currency }} {{ number_format($item->line_total, 2) }}</td>
                            <td>
                                @if(data_get($item->configuration, 'delivery_date'))
                                    {{ \Illuminate\Support\Carbon::parse(data_get($item->configuration, 'delivery_date'))->toFormattedDateString() }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item->artwork)
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('admin.artworks.show', $item->artwork) }}" class="font-semibold text-slate-900 hover:text-rose-700">
                                            {{ $item->artwork->original_name }}
                                        </a>
                                        <a href="{{ route('admin.artworks.download', $item->artwork) }}" class="text-xs font-bold text-rose-700 hover:text-rose-900">
                                            Download artwork
                                        </a>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <aside class="rounded-xl bg-white p-5 shadow-sm">
        <h3 class="font-bold">Update Status</h3>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-3 space-y-3">
            @csrf
            @method('PATCH')
            <select name="status" class="w-full rounded-xl border-slate-300">
                @foreach(['pending','processing','printing','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="w-full rounded-xl border-slate-300">
                @foreach(['unpaid','paid','failed','refunded'] as $ps)
                    <option value="{{ $ps }}" @selected($order->payment_status === $ps)>{{ ucfirst($ps) }}</option>
                @endforeach
            </select>
            <button class="w-full rounded-xl bg-slate-900 py-2 text-sm font-bold text-white">Save</button>
        </form>
        @php
            $shippingLabel = data_get(config('shop.shipping_options'), $order->shipping_option . '.label')
                ?: str($order->shipping_option)->replace('_', ' ')->title();
        @endphp
        <div class="mt-4 text-sm">
            <p><span class="font-semibold">Total:</span> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p><span class="font-semibold">Shipping:</span> {{ $shippingLabel }}</p>
            <p><span class="font-semibold">Address:</span> {{ $order->shipping_address_line1 }}, {{ $order->shipping_country }}</p>
        </div>

        <div class="mt-5 border-t border-slate-200 pt-5">
            <h3 class="font-bold">Delete Order</h3>
            <p class="mt-2 text-sm text-slate-600">This removes the order and its line items.</p>
            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-3" onsubmit='return confirm(@js('Delete order ' . $order->order_number . '? This cannot be undone.'));'>
                @csrf
                @method('DELETE')
                <button class="w-full rounded-xl bg-rose-700 py-2 text-sm font-bold text-white hover:bg-rose-800">Delete Order</button>
            </form>
        </div>
    </aside>
</div>
@endsection
