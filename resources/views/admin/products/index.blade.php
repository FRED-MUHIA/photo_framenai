@extends('layouts.admin')

@section('heading', 'Products')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.products.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Add Product</a>
</div>
<div class="rounded-xl bg-white p-5 shadow-sm">
    <table class="w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Name</th><th>Categories</th><th>Base Price</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $product->name }}</td>
                    <td>
                        {{ $product->categories->pluck('name')->join(', ') ?: $product->category?->name }}
                    </td>
                    <td>{{ config('shop.currency_symbol') }} {{ number_format($product->base_price, 2) }}</td>
                    <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.products.edit', $product) }}" class="mr-2 text-xs font-bold">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">@csrf @method('DELETE')<button class="text-xs font-bold text-rose-700">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
