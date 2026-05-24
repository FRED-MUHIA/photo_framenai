@extends('layouts.admin')

@section('heading', 'Order Details')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">{{ $order->order_number }}</h2>
        <p class="text-sm text-slate-500">{{ $order->customer_name }} | {{ $order->customer_email }}</p>
        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Product</th><th>Qty</th><th>Unit</th><th>Total</th><th>Delivery Date</th><th>Artwork</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">{{ $item->product_name }}</td>
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
                            <td>{{ $item->artwork?->original_name ?? '-' }}</td>
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
        <div class="mt-4 text-sm">
            <p><span class="font-semibold">Total:</span> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p><span class="font-semibold">Shipping:</span> {{ ucfirst($order->shipping_option) }}</p>
            <p><span class="font-semibold">Address:</span> {{ $order->shipping_address_line1 }}, {{ $order->shipping_country }}</p>
        </div>
    </aside>
</div>
@endsection
