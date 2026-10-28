@extends('layouts.storefront')

@section('meta_title', $cmsHome?->meta_title ?: 'Photo Frames | Frame Your Favorite Moments')
@section('meta_description', $cmsHome?->meta_description ?: 'Find the perfect frame for your photos, art, and memories. Explore picture frames and custom prints from Photo Frames, with delivery across Kenya.')
@section('meta_image', \App\Support\PublicAssetUrl::resolve(data_get($storefrontSections, 'storefront_hero.items.0.image_url')) ?: asset('images/frames/hero.webp'))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/frames-home.css') }}?v=40">
<link rel="preload" as="image" href="{{ \App\Support\PublicAssetUrl::resolve(data_get($storefrontSections, 'storefront_hero.items.0.image_url')) ?: asset('images/frames/hero.webp') }}">
@endpush

@section('content')
@php
    $homeHero = $storefrontSections['storefront_hero'];
    $homeFeatured = $storefrontSections['storefront_featured'];
    $homeIntro = $storefrontSections['storefront_intro'];
    $homeCategories = $storefrontSections['storefront_categories'];
    $homeColors = $storefrontSections['storefront_colors'];
    $homeProducts = $storefrontSections['storefront_products'];
    $homePrints = $storefrontSections['storefront_prints'];
    $homeValues = $storefrontSections['storefront_values'];
    $homeImage = fn ($url) => preg_match('~^https?://~i', (string) $url) ? $url : asset(ltrim((string) $url, '/'));
@endphp
<div id="homepage" class="pf-home">
    <section class="pf-hero" aria-labelledby="hero-heading" x-data="{ slides: @js(collect($homeHero['items'] ?? [])->map(fn ($item) => ['src' => $homeImage($item['image_url'] ?? ''), 'alt' => $item['title'] ?? ''])->values()), active: 0, timer: null, next() { this.active = (this.active + 1) % this.slides.length }, start() { window.clearInterval(this.timer); if (this.slides.length > 1) this.timer = window.setInterval(() => this.next(), 4500) }, restart() { this.start() } }" x-init="start()" @focusin="window.clearInterval(timer)" @focusout="restart()">
        <div class="pf-hero-copy">
            <p class="pf-eyebrow">{{ $homeHero['subtitle'] }}</p>
            <h1 id="hero-heading">{{ $homeHero['title'] }}</h1>
            <p>{{ $homeHero['content'] }}</p>
            <a class="pf-button" href="{{ $homeHero['button_url'] }}">{{ $homeHero['button_label'] }}</a>
        </div>
        <div class="pf-hero-visual">
            <template x-for="(slide, index) in slides" :key="slide.src">
                <img x-show="active === index" x-transition:enter="pf-slide-enter" x-transition:enter-start="pf-slide-enter-start" x-transition:enter-end="pf-slide-enter-end" x-transition:leave="pf-slide-leave" x-transition:leave-start="pf-slide-leave-start" x-transition:leave-end="pf-slide-leave-end" class="pf-hero-image" :src="slide.src" :alt="slide.alt" width="1536" height="1024" :fetchpriority="index === 0 ? 'high' : 'auto'">
            </template>
            <div class="pf-hero-slider-controls" aria-label="Hero image slider controls">
                <div class="pf-hero-slider-dots" aria-label="Choose an image">
                    <template x-for="(slide, index) in slides" :key="slide.src">
                        <button type="button" :aria-label="`Show image ${index + 1}`" :aria-current="active === index ? 'true' : 'false'" :class="active === index ? 'is-active' : ''" @click="active = index; restart()"></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <section class="pf-featured pf-container" aria-label="Explore frames and prints">
        @foreach($homeFeatured['items'] ?? [] as $item)
        <a class="pf-feature-card" href="{{ $item['link_url'] ?? '/products' }}"><div class="pf-image-wrap"><img src="{{ $homeImage($item['image_url'] ?? '') }}" alt="{{ $item['title'] ?? '' }}" width="600" height="400"></div><div class="pf-feature-caption"><h2>{{ $item['title'] ?? '' }}</h2><span>{{ $item['subtitle'] ?? '' }}</span></div></a>
        @endforeach
    </section>

    <section class="pf-intro pf-intro-band">
        <div class="pf-intro-inner">
            <p class="pf-eyebrow">{{ $homeIntro['subtitle'] }}</p>
            <h2>{{ $homeIntro['title'] }}</h2>
            <p>{{ $homeIntro['content'] }}</p>
            <a class="pf-button" href="{{ $homeIntro['button_url'] }}">{{ $homeIntro['button_label'] }}</a>
        </div>
    </section>

    <div class="pf-collections">
        <section id="frame-types" class="pf-collection pf-container" aria-labelledby="types-heading">
            <div class="pf-section-heading"><h2 id="types-heading">{{ $homeCategories['title'] }}</h2><div class="pf-color-slider-actions"><button type="button" class="pf-color-slider-button" data-category-slider="previous" aria-label="Previous product categories">‹</button><button type="button" class="pf-color-slider-button" data-category-slider="next" aria-label="Next product categories">›</button><a class="pf-button pf-button-outline" href="{{ $homeCategories['button_url'] }}">{{ $homeCategories['button_label'] }} <span aria-hidden="true">↗</span></a></div></div>
            <div class="pf-color-slider-viewport pf-category-slider-viewport" id="pf-category-slider-viewport" aria-label="Product categories" tabindex="0">
            <div class="pf-grid pf-grid-four pf-category-slider-track">
                @forelse($categories as $category)
                @php($categoryImage = $category->homepage_image ?: asset('images/frames/' . ['print-paper', 'print-canvas', 'print-metal', 'print-acrylic', 'print-wood', 'custom-printing'][$loop->index % 6] . '.webp'))
                <a class="pf-category" href="{{ route('products.index', ['category' => $category->slug]) }}"><div class="pf-image-wrap">@if($categoryImage)<img loading="lazy" src="{{ $categoryImage }}" alt="{{ $category->name }}" width="400" height="500">@else<div class="pf-category-placeholder">{{ $category->name }}</div>@endif</div><h3>{{ $category->name }}</h3></a>
                @empty
                    <p class="pf-category-empty">Product categories will appear here once active products are assigned to them.</p>
                @endforelse
            </div>
            </div>
        </section>
        <section id="frame-colors" class="pf-collection pf-container" aria-labelledby="colors-heading">
            <div class="pf-section-heading"><h2 id="colors-heading">{{ $homeColors['title'] }}</h2><div class="pf-color-slider-actions"><button type="button" class="pf-color-slider-button" data-color-slider="previous" aria-label="Previous frame colors">‹</button><button type="button" class="pf-color-slider-button" data-color-slider="next" aria-label="Next frame colors">›</button><a class="pf-button pf-button-outline" href="{{ $homeColors['button_url'] }}">{{ $homeColors['button_label'] }} <span aria-hidden="true">↗</span></a></div></div>
            <div class="pf-color-slider-viewport" id="pf-color-slider-viewport" aria-label="Frame colors" tabindex="0">
                <div class="pf-grid pf-grid-six pf-color-slider-track">
                    @foreach($homeColors['items'] ?? [] as $item)
                    <a class="pf-category" href="{{ route('products.index', ['q' => $item['link_url'] ?? $item['title']]) }}"><div class="pf-image-wrap"><img loading="lazy" src="{{ $homeImage($item['image_url'] ?? '') }}" alt="{{ $item['title'] ?? '' }} frame finish" width="300" height="300"></div><h3>{{ $item['title'] ?? '' }}</h3></a>
                    @endforeach
                </div>
            </div>
        </section>
        <section id="frame-decor" class="pf-collection pf-container" aria-labelledby="decor-heading">
            <div class="pf-section-heading"><h2 id="decor-heading">{{ $homeProducts['title'] }}</h2><div class="pf-color-slider-actions"><button type="button" class="pf-color-slider-button" data-product-slider="previous" aria-label="Previous products">‹</button><button type="button" class="pf-color-slider-button" data-product-slider="next" aria-label="Next products">›</button><a class="pf-button pf-button-outline" href="{{ $homeProducts['button_url'] }}">{{ $homeProducts['button_label'] }} <span aria-hidden="true">↗</span></a></div></div>
            <div class="pf-color-slider-viewport pf-product-slider-viewport" id="pf-product-slider-viewport" aria-label="Popular products" tabindex="0">
                <div class="pf-grid pf-grid-four pf-product-slider-track">
                    @forelse($sliderProducts as $product)
                        <a class="pf-category pf-product-card" href="{{ route('products.show', $product) }}">
                            <div class="pf-image-wrap"><img loading="lazy" src="{{ $product->thumbnail }}" alt="{{ $product->name }}" width="500" height="500"></div>
                            <h3>{{ $product->name }}</h3>
                            <p>From {{ config('shop.currency_symbol') }} {{ number_format((float) $product->base_price, 2) }}</p>
                        </a>
                    @empty
                        <p class="pf-product-slider-empty">No products with images are available yet. <a href="{{ route('products.index') }}">Browse all products</a>.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    <section id="custom-prints" class="pf-prints pf-container" aria-labelledby="prints-heading">
        <div class="pf-centered-heading"><p class="pf-eyebrow">{{ $homePrints['subtitle'] }}</p><h2 id="prints-heading">{{ $homePrints['title'] }}</h2><p>{{ $homePrints['content'] }}</p></div>
        <div class="pf-print-grid">
            @foreach($homePrints['items'] ?? [] as $item)
            <a class="pf-category" href="{{ route('products.index', ['q' => $item['link_url'] ?? $item['title']]) }}"><div class="pf-image-wrap"><img loading="lazy" src="{{ $homeImage($item['image_url'] ?? '') }}" alt="{{ $item['title'] ?? '' }} material and edge detail" width="400" height="400"></div><h3>{{ $item['title'] ?? '' }}</h3></a>
            @endforeach
        </div>
    </section>

    <section class="pf-values"><h2>{{ $homeValues['title'] }}</h2><p>{{ $homeValues['content'] }}</p><div class="pf-values-grid pf-container">
        @foreach($homeValues['items'] ?? [] as $item)
        <div><span class="pf-value-icon" aria-hidden="true">
            @if($loop->index % 6 === 0)
                <svg viewBox="0 0 24 24"><path d="M20.8 8.7c0 5.3-8.8 11-8.8 11s-8.8-5.7-8.8-11a4.7 4.7 0 0 1 8.8-2.3 4.7 4.7 0 0 1 8.8 2.3Z"/></svg>
            @elseif($loop->index % 6 === 1)
                <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="1.5"/><rect x="7" y="7" width="10" height="10" rx=".5"/></svg>
            @elseif($loop->index % 6 === 2)
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m4 17 5-5 3 3 3-4 5 6"/></svg>
            @elseif($loop->index % 6 === 3)
                <svg viewBox="0 0 24 24"><path d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path d="m19 15 .9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z"/></svg>
            @elseif($loop->index % 6 === 4)
                <svg viewBox="0 0 24 24"><path d="M3 7h11v11H3zM14 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18" cy="19" r="1.5"/></svg>
            @else
                <svg viewBox="0 0 24 24"><path d="M4 13v-1a8 8 0 0 1 16 0v1"/><path d="M4 13H3v4a2 2 0 0 0 2 2h2v-6H4ZM20 13h1v4a2 2 0 0 1-2 2h-2v-6h3ZM9 20h4"/></svg>
            @endif
        </span><h3>{!! nl2br(e(str_replace('|', "\n", $item['title'] ?? ''))) !!}</h3></div>
        @endforeach
    </div></section>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const viewport = document.getElementById('pf-color-slider-viewport');
    if (!viewport) return;
    const step = () => {
        const card = viewport.querySelector('.pf-category');
        if (!card) return viewport.clientWidth;
        const gap = parseFloat(getComputedStyle(viewport.querySelector('.pf-color-slider-track')).gap) || 0;
        return card.getBoundingClientRect().width + gap;
    };
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let timer = null;
    let paused = false;
    const move = direction => {
        const max = viewport.scrollWidth - viewport.clientWidth;
        if (direction === 'next') {
            viewport.scrollTo({ left: viewport.scrollLeft >= max - 4 ? 0 : viewport.scrollLeft + step(), behavior: 'smooth' });
        } else {
            viewport.scrollTo({ left: viewport.scrollLeft <= 4 ? max : viewport.scrollLeft - step(), behavior: 'smooth' });
        }
    };
    const stop = () => { window.clearInterval(timer); timer = null; };
    const start = () => {
        stop();
        if (!reducedMotion && !paused && viewport.scrollWidth > viewport.clientWidth + 4) {
            timer = window.setInterval(() => move('next'), 4200);
        }
    };
    document.querySelectorAll('[data-color-slider]').forEach(button => button.addEventListener('click', () => {
        move(button.dataset.colorSlider);
        start();
    }));
    viewport.addEventListener('keydown', event => {
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            move(event.key === 'ArrowRight' ? 'next' : 'previous');
            start();
        }
    });
    viewport.addEventListener('mouseenter', () => { paused = true; stop(); });
    viewport.addEventListener('mouseleave', () => { paused = false; start(); });
    viewport.addEventListener('focusin', () => { paused = true; stop(); });
    viewport.addEventListener('focusout', () => window.setTimeout(() => {
        if (!viewport.contains(document.activeElement)) { paused = false; start(); }
    }, 0));
    viewport.addEventListener('touchstart', () => { paused = true; stop(); }, { passive: true });
    viewport.addEventListener('touchend', () => window.setTimeout(() => { paused = false; start(); }, 5000), { passive: true });
    document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());
    window.addEventListener('resize', start);
    start();
})();
(() => {
    const viewport = document.getElementById('pf-product-slider-viewport');
    if (!viewport) return;
    const step = () => {
        const card = viewport.querySelector('.pf-product-card');
        if (!card) return viewport.clientWidth;
        const gap = parseFloat(getComputedStyle(viewport.querySelector('.pf-product-slider-track')).gap) || 0;
        return card.getBoundingClientRect().width + gap;
    };
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let timer = null;
    let paused = false;
    const move = direction => {
        const max = viewport.scrollWidth - viewport.clientWidth;
        if (max <= 4) return;
        if (direction === 'next') viewport.scrollTo({ left: viewport.scrollLeft >= max - 4 ? 0 : viewport.scrollLeft + step(), behavior: 'smooth' });
        else viewport.scrollTo({ left: viewport.scrollLeft <= 4 ? max : viewport.scrollLeft - step(), behavior: 'smooth' });
    };
    const stop = () => { window.clearInterval(timer); timer = null; };
    const start = () => {
        stop();
        if (!reducedMotion && !paused && viewport.scrollWidth > viewport.clientWidth + 4) timer = window.setInterval(() => move('next'), 4200);
    };
    document.querySelectorAll('[data-product-slider]').forEach(button => button.addEventListener('click', () => { move(button.dataset.productSlider); start(); }));
    viewport.addEventListener('keydown', event => {
        if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
        event.preventDefault(); move(event.key === 'ArrowRight' ? 'next' : 'previous'); start();
    });
    viewport.addEventListener('mouseenter', () => { paused = true; stop(); });
    viewport.addEventListener('mouseleave', () => { paused = false; start(); });
    viewport.addEventListener('focusin', () => { paused = true; stop(); });
    viewport.addEventListener('focusout', () => window.setTimeout(() => { if (!viewport.contains(document.activeElement)) { paused = false; start(); } }, 0));
    viewport.addEventListener('touchstart', () => { paused = true; stop(); }, { passive: true });
    viewport.addEventListener('touchend', () => window.setTimeout(() => { paused = false; start(); }, 5000), { passive: true });
    document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());
    window.addEventListener('resize', start);
    start();
})();
(() => {
    const viewport = document.getElementById('pf-category-slider-viewport');
    if (!viewport) return;
    const step = () => {
        const card = viewport.querySelector('.pf-category-slider-track > .pf-category');
        if (!card) return viewport.clientWidth;
        const gap = parseFloat(getComputedStyle(viewport.querySelector('.pf-category-slider-track')).gap) || 0;
        return card.getBoundingClientRect().width + gap;
    };
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let timer = null;
    let paused = false;
    const move = direction => {
        const max = viewport.scrollWidth - viewport.clientWidth;
        if (max <= 4) return;
        if (direction === 'next') viewport.scrollTo({ left: viewport.scrollLeft >= max - 4 ? 0 : viewport.scrollLeft + step(), behavior: 'smooth' });
        else viewport.scrollTo({ left: viewport.scrollLeft <= 4 ? max : viewport.scrollLeft - step(), behavior: 'smooth' });
    };
    const stop = () => { window.clearInterval(timer); timer = null; };
    const start = () => {
        stop();
        if (!reducedMotion && !paused && viewport.scrollWidth > viewport.clientWidth + 4) timer = window.setInterval(() => move('next'), 4200);
    };
    document.querySelectorAll('[data-category-slider]').forEach(button => button.addEventListener('click', () => { move(button.dataset.categorySlider); start(); }));
    viewport.addEventListener('keydown', event => {
        if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
        event.preventDefault(); move(event.key === 'ArrowRight' ? 'next' : 'previous'); start();
    });
    viewport.addEventListener('mouseenter', () => { paused = true; stop(); });
    viewport.addEventListener('mouseleave', () => { paused = false; start(); });
    viewport.addEventListener('focusin', () => { paused = true; stop(); });
    viewport.addEventListener('focusout', () => window.setTimeout(() => { if (!viewport.contains(document.activeElement)) { paused = false; start(); } }, 0));
    viewport.addEventListener('touchstart', () => { paused = true; stop(); }, { passive: true });
    viewport.addEventListener('touchend', () => window.setTimeout(() => { paused = false; start(); }, 5000), { passive: true });
    document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());
    window.addEventListener('resize', start);
    start();
})();
</script>
@endpush
