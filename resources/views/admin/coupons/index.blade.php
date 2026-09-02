@extends('layouts.admin')

@section('heading', 'Coupons')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.coupons.create') }}" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-center text-sm font-bold text-white sm:w-auto">Add Coupon</a>
</div>
<div class="rounded-xl bg-white p-4 shadow-sm sm:p-5">
    <div class="overflow-x-auto">
    <table class="min-w-[48rem] w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Code</th><th>Type</th><th>Value</th><th>Usage</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $coupon->code }}</td>
                    <td>{{ ucfirst($coupon->type) }}</td>
                    <td>{{ $coupon->type === 'percentage' ? number_format($coupon->value, 2).'%' : config('shop.currency_symbol').' '.number_format($coupon->value,2) }}</td>
                    <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                    <td>{{ $coupon->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="mr-2 text-xs font-bold">Edit</a>
                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline">@csrf @method('DELETE')<button class="text-xs font-bold text-rose-700">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $coupons->links() }}</div>
</div>
@endsection
