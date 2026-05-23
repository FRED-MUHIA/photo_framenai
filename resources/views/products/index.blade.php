@extends('layouts.storefront')

@section('meta_title', 'Print Products - NaiPrinters')

@section('content')
<section class="bg-white">
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="np-type-page-title">Print Products</h1>
            <p class="mt-2 text-sm text-slate-600">Choose a product type and configure your exact print specs.</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search products..." class="rounded-xl border-slate-300">
            <select name="category" class="rounded-xl border-slate-300">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
        </form>
    </div>

    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($products as $product)
            @php
                $thumbnailUrl = \App\Support\PublicAssetUrl::resolve($product->thumbnail);
            @endphp
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="h-44 overflow-hidden rounded-xl bg-slate-100">
                    @if($thumbnailUrl)
                        <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="mt-4 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">
                            {{ $product->categories->pluck('name')->join(' / ') ?: $product->category?->name }}
                        </p>
                        <h2 class="text-lg font-extrabold">{{ $product->name }}</h2>
                    </div>
                    @if($product->is_featured)
                        <span class="rounded-full bg-teal-50 px-2 py-1 text-xs font-bold text-teal-700">Popular</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ $product->short_description }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-900">{{ config('shop.currency_symbol') }} {{ number_format($product->base_price, 2) }}</span>
                    <a href="{{ route('products.show', $product) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-bold hover:border-slate-400">Configure</a>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500 sm:col-span-2 lg:col-span-3">No products found for your filter.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
</section>
@endsection
