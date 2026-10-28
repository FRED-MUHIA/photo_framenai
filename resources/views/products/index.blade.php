@extends('layouts.storefront')

@section('meta_title', 'Print Products - ' . config('app.name'))
@section('meta_description', 'Browse custom print products from ' . config('app.name') . ', including business cards, flyers, banners, stationery, and branded apparel with delivery across Kenya.')
@section('meta_robots', ($search !== '' || $categorySlug !== '' || $products->currentPage() > 1) ? 'noindex, follow' : 'index, follow, max-image-preview:large')
@section('canonical_url', rtrim(config('seo.site_url'), '/') . route('products.index', [], false))

@push('head')
<style>
    .product-index-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }
    .product-index-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #eee;
        border-radius: 9px;
        background: #fff;
        color: #171717;
        text-decoration: none;
        transition: box-shadow .18s ease, transform .18s ease;
    }
    .product-index-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgb(30 25 20 / .08);
    }
    .product-index-card-image {
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: #f3f1ed;
    }
    .product-index-card-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-index-card-copy {
        padding: 8px 9px 9px;
    }
    .product-index-card-title {
        display: -webkit-box;
        overflow: hidden;
        min-height: 2.6em;
        color: #171717;
        font-size: 11px;
        font-weight: 500;
        line-height: 1.3;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    body:has(.pf-header) main .product-index-grid .product-index-card-title {
        font-size: 12px !important;
        line-height: 1.3 !important;
    }
    .product-index-card-price {
        margin-top: 3px;
        color: #a28d7e;
        font-size: 10px;
        line-height: 1.3;
    }
    .product-index-card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 7px;
    }
    .product-index-card-tag {
        max-width: 100%;
        overflow: hidden;
        border: 1px solid #e9e6e1;
        border-radius: 999px;
        padding: 2px 6px;
        color: #82796f;
        font-size: 8px;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    @media (max-width: 480px) {
        .product-index-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .product-index-card-copy { padding: 8px; }
        .product-index-card-title { font-size: 12px; }
        .product-index-card-price { font-size: 11px; }
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

    <div class="product-index-grid mt-8">
        @forelse($products as $product)
            @php
                $thumbnailUrl = \App\Support\PublicAssetUrl::image($product->thumbnail, 520, 64);
                $imageLoading = $loop->first ? 'eager' : 'lazy';
                $imagePriority = $loop->first ? 'high' : 'auto';
                $productTags = collect(data_get($product->metadata, 'tags', []))
                    ->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
                    ->map(fn ($tag) => trim($tag))
                    ->take(2);
                if ($productTags->isEmpty()) {
                    $productTags = $product->categories->pluck('name')->filter()->take(2);
                }
            @endphp
            <a href="{{ route('products.show', $product) }}" class="product-index-card">
                <div class="product-index-card-image">
                    @if($thumbnailUrl)
                        <img loading="{{ $imageLoading }}" fetchpriority="{{ $imagePriority }}" decoding="async" src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                        <div hidden class="product-image-fallback"><span aria-hidden="true">PF</span><strong>{{ $product->name }}</strong></div>
                    @else
                        <div class="product-image-fallback"><span aria-hidden="true">PF</span><strong>{{ $product->name }}</strong></div>
                    @endif
                </div>
                <div class="product-index-card-copy">
                    <h2 class="product-index-card-title">{{ $product->name }}</h2>
                    <p class="product-index-card-price">{{ config('shop.currency_symbol') }}{{ number_format($product->base_price, 2) }}</p>
                    @if($productTags->isNotEmpty())
                        <div class="product-index-card-tags" aria-label="Product tags">
                            @foreach($productTags as $tag)
                                <span class="product-index-card-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">No products found for your filter.</div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
</section>
@endsection
