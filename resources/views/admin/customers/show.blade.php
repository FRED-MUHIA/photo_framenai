@extends('layouts.admin')

@section('heading', 'Customer Profile')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">{{ $customer->name }}</h2>
        <p class="text-sm text-slate-500">{{ $customer->email }} | {{ $customer->phone }}</p>
        <h3 class="mt-6 font-bold">Orders</h3>
        <div class="mt-3 space-y-2">
            @forelse($customer->orders as $order)
                <div class="rounded-lg border border-slate-200 p-3 text-sm">{{ $order->order_number }} - {{ ucfirst($order->status) }} - {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</div>
            @empty
                <p class="text-sm text-slate-500">No orders yet.</p>
            @endforelse
        </div>
    </div>
    <aside class="rounded-xl bg-white p-5 shadow-sm">
        <h3 class="font-bold">Update Role</h3>
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="mt-3 space-y-3">
            @csrf
            @method('PATCH')
            <select name="role" class="w-full rounded-xl border-slate-300">
                <option value="customer" @selected($customer->role === 'customer')>Customer</option>
                <option value="admin" @selected($customer->role === 'admin')>Admin</option>
            </select>
            <button class="w-full rounded-xl bg-slate-900 py-2 text-sm font-bold text-white">Save Role</button>
        </form>
    </aside>
</div>
@endsection
