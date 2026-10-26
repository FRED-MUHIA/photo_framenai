@extends('layouts.storefront')

@section('meta_title', 'Print Products - ' . config('app.name'))
@section('meta_description', 'Browse custom print products from ' . config('app.name') . ', including business cards, flyers, banners, stationery, and branded apparel with delivery across Kenya.')
@section('meta_robots', ($search !== '' || $categorySlug !== '' || $products->currentPage() > 1) ? 'noindex, follow' : 'index, follow, max-image-preview:large')
@section('canonical_url', rtrim(config('seo.site_url'), '/') . route('products.index', [], false))

@push('head')
<style>
    .product-index-card-copy {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
</style>
@endpush

@section('content')
<section class="product-index-page">
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="np-type-page-title">Print Products</h1>
            <p class="mt-2 text-sm text-slate-600">Choose a product type and configure your exact print specs.</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search products..." class="rounded-xl border-slate-300">
            <select name="category" class="rounded-xl border-slate-300" onchange="this.form.submit()">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
        </form>
    </div>

    <div class="mt-6 flex gap-2 overflow-x-auto pb-1">
        <a
            href="{{ route('products.index', array_filter(['q' => $search])) }}"
            class="inline-flex h-9 shrink-0 items-center rounded-full border px-4 text-sm font-bold transition {{ $categorySlug === '' ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400' }}"
        >
            All
        </a>
        @foreach($categories as $category)
            <a
                href="{{ route('products.index', array_filter(['q' => $search, 'category' => $category->slug])) }}"
                class="inline-flex h-9 shrink-0 items-center rounded-full border px-4 text-sm font-bold transition {{ $categorySlug === $category->slug ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400' }}"
            >
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Showing category</p>
        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
            {{ $selectedCategory?->name ?? 'All Products' }}
        </h2>
        @if($selectedCategory?->description)
            <p class="mt-1 text-sm text-slate-600">{{ $selectedCategory->description }}</p>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
            @php
                $thumbnailUrl = \App\Support\PublicAssetUrl::image($product->thumbnail, 520, 64);
                $imageLoading = $loop->first ? 'eager' : 'lazy';
                $imagePriority = $loop->first ? 'high' : 'auto';
            @endphp
            <a href="{{ route('products.show', $product) }}" class="block rounded-xl border border-slate-200 bg-white p-3 text-slate-950 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:rounded-2xl sm:p-4">
                <div class="aspect-square overflow-hidden rounded-lg bg-slate-100 sm:rounded-xl">
                    @if($thumbnailUrl)
                        <img loading="{{ $imageLoading }}" fetchpriority="{{ $imagePriority }}" decoding="async" src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                        <div hidden class="product-image-fallback"><span aria-hidden="true">PF</span><strong>{{ $product->name }}</strong></div>
                    @else
                        <div class="product-image-fallback"><span aria-hidden="true">PF</span><strong>{{ $product->name }}</strong></div>
                    @endif
                </div>
                <div class="mt-3 flex items-start justify-between gap-2 sm:mt-4 sm:gap-3">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-teal-700 sm:text-xs sm:tracking-wider">
                            {{ $product->categories->pluck('name')->join(' / ') ?: $product->category?->name }}
                        </p>
                        <h2 class="mt-1 text-sm font-extrabold leading-tight sm:text-lg">{{ $product->name }}</h2>
                    </div>
                    @if($product->is_featured)
                        <span class="hidden rounded-full bg-teal-50 px-2 py-1 text-xs font-bold text-teal-700 sm:inline-flex">Popular</span>
                    @endif
                </div>
                <p class="product-index-card-copy mt-2 text-xs leading-5 text-slate-600 sm:text-sm">{{ $product->short_description }}</p>
                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 sm:mt-4">
                    <span class="text-xs font-extrabold text-slate-900 sm:text-sm">{{ config('shop.currency_symbol') }} {{ number_format($product->base_price, 2) }}</span>
                    <span class="rounded-lg border border-slate-300 px-2 py-1 text-[11px] font-bold hover:border-slate-400 sm:px-3 sm:py-1.5 sm:text-xs">Order Now</span>
                </div>
            </a>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500 sm:col-span-2 lg:col-span-3 xl:col-span-4">No products found for your filter.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
</section>
@endsection
