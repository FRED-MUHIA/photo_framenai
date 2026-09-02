@extends('layouts.admin')

@section('heading', 'Customers')

@section('content')
<div class="rounded-xl bg-white p-4 shadow-sm sm:p-5">
    <div class="overflow-x-auto">
    <table class="min-w-[38rem] w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Name</th><th>Email</th><th>Orders</th><th></th></tr></thead>
        <tbody>
            @foreach($customers as $customer)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->orders_count }}</td>
                    <td><a href="{{ route('admin.customers.show', $customer) }}" class="text-xs font-bold">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
</div>
@endsection
