@extends('layouts.admin')

@section('heading', 'Orders')

@section('content')
<form method="GET" class="mb-4 flex items-center gap-2">
    <select name="status" class="rounded-xl border-slate-300 text-sm">
        <option value="">All statuses</option>
        @foreach(['pending','processing','printing','shipped','completed','cancelled'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Filter</button>
</form>
<div class="rounded-xl bg-white p-5 shadow-sm">
    <table class="w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Order</th><th>Customer</th><th>Status</th><th>Total</th><th></th></tr></thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
