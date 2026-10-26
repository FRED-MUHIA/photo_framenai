<a class="pf-skip" href="#homepage">Skip to content</a>
@php
    $headerNav = $siteSettings->has('header_nav') ? json_decode($siteSettings->get('header_nav', '[]'), true) : null;
    if (! is_array($headerNav)) {
        $headerNav = [
            ['label' => 'All Frames', 'url' => '/products'],
            ['label' => 'Popular Frames', 'url' => '/products', 'items' => [['label' => 'Floating Frames', 'url' => '/products?q=floating'], ['label' => 'Wood Frames', 'url' => '/products?q=wood'], ['label' => 'Metal Frames', 'url' => '/products?q=metal'], ['label' => 'Ornate Frames', 'url' => '/products?q=ornate']]],
            ['label' => 'Customize Picture', 'url' => '/customize-frame'],
            ['label' => 'Shop Frames by', 'url' => '/products', 'items' => [['label' => 'Frame Types', 'url' => '/products'], ['label' => 'Frame Colors', 'url' => '/products'], ['label' => 'Decor Styles', 'url' => '/products']]],
            ['label' => 'Custom Prints', 'url' => '/products', 'items' => [['label' => 'Canvas Prints', 'url' => '/products?q=canvas'], ['label' => 'Paper Prints', 'url' => '/products?q=paper'], ['label' => 'Metal Prints', 'url' => '/products?q=metal']]],
            ['label' => 'Resources', 'url' => '/help', 'items' => [['label' => 'Help & FAQs', 'url' => '/help'], ['label' => 'Contact Us', 'url' => '/contact']]],
            ['label' => 'Clearance', 'url' => '/products?q=clearance'],
        ];
    }
    $menuHref = fn ($target) => preg_match('~^(https?://|mailto:|tel:|#)~i', (string) $target) ? $target : url(ltrim((string) $target, '/'));
    $headerLogo = \App\Support\PublicAssetUrl::resolve($siteSettings->get('header_logo_url'));
    $headerBrand = $siteSettings->get('header_brand_label', $siteSettings->get('site_name', config('app.name', 'Photo Frames')));
@endphp
<header class="pf-header">
    <div class="pf-contact-strip" aria-label="Contact and delivery information">
        <div class="pf-contact-strip-details">
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->get('header_phone', '+254 745 730 906')) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.8a15 15 0 0 0 4.7 4.7L15 13l5 2v4a1 1 0 0 1-1 1C10 20 4 14 4 5a1 1 0 0 1 1-1Z"/></svg>{{ $siteSettings->get('header_phone', '+254 745 730 906') }}</a>
            <a href="mailto:{{ $siteSettings->get('header_email', 'orders@photostore.co.ke') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>{{ $siteSettings->get('header_email', 'orders@photostore.co.ke') }}</a>
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $siteSettings->get('header_location', 'Nairobi, Kenya') }}</span>
        </div>
        <div class="pf-contact-strip-promos">
            @php
                $announcementText = trim($siteSettings->get('header_announcement', 'Your memories. Beautifully framed.') . ' ' . $siteSettings->get('header_announcement_detail', 'Made for your space, delivered across Kenya.'));
            @endphp
            <span class="pf-contact-announcement pf-typewriter" data-typewriter-text="{{ $announcementText }}" aria-label="{{ $announcementText }}" aria-live="off" aria-busy="true"></span>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}">{{ $siteSettings->get('header_track_order_label', 'Track order') }}</a>
        </div>
    </div>
    <div class="pf-topbar">
        <a class="pf-logo" href="{{ route('home') }}" aria-label="{{ $headerBrand }} home">@if($headerLogo)<img src="{{ $headerLogo }}" alt="{{ $headerBrand }}" class="pf-header-logo-image">@else<span class="pf-logo-mark" aria-hidden="true"></span>{{ $headerBrand }}@endif</a>
        <form class="pf-search" action="{{ route('products.index') }}" method="get" role="search">
            <label class="pf-sr-only" for="frame-search">Search frames</label>
            <input id="frame-search" name="q" type="search" placeholder="{{ $siteSettings->get('header_search_placeholder', 'Search frames by color, material, style…') }}" value="{{ request('q') }}">
            <button type="submit" aria-label="Search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg></button>
        </form>
        <div class="pf-header-actions">
            <a href="{{ route('pages.help') }}" aria-label="Help" title="Help"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 0 1 5 0c0 2-2.5 2-2.5 4m0 2v2"/></svg></a>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" aria-label="My account" title="My account"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/></svg></a>
            <a class="pf-cart" href="{{ route('cart.index') }}" aria-label="Shopping cart" title="Shopping cart"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M2 3h3l3 13h11l3-9H6"/><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/></svg>@if(count(session('print_cart', [])))<span>{{ count(session('print_cart', [])) }}</span>@endif</a>
        </div>
    </div>
    <nav class="pf-nav" aria-label="Main navigation">
        @foreach($headerNav as $navItem)
            @php
                $navChildren = is_array($navItem['items'] ?? null) ? $navItem['items'] : [];
            @endphp
            @if(count($navChildren))
                @php
                    $printImageDefaults = [
                        'canvas prints' => '/images/frames/print-canvas.webp',
                        'paper prints' => '/images/frames/print-paper.webp',
                        'metal prints' => '/images/frames/print-metal.webp',
                        'wood prints' => '/images/frames/print-wood.webp',
                        'acrylic prints' => '/images/frames/print-acrylic.webp',
                    ];
                    $frameImageDefaults = [
                        'floating frames' => '/images/frames/type-floating.webp',
                        'wood frames' => '/images/frames/color-brown.webp',
                        'metal frames' => '/images/frames/color-black.webp',
                        'ornate frames' => '/images/frames/type-ornate.webp',
                        'gold frames' => '/images/frames/color-gold.webp',
                        'natural frames' => '/images/frames/color-natural.webp',
                    ];
                    $navChildren = array_map(function ($child) use ($printImageDefaults, $frameImageDefaults, $navItem) {
                        if (empty($child['image'])) {
                            $childLabel = strtolower(trim((string) ($child['label'] ?? '')));
                            $menuLabel = strtolower((string) ($navItem['label'] ?? ''));
                            if (str_contains($menuLabel, 'print')) {
                                $child['image'] = $printImageDefaults[$childLabel] ?? '';
                            } elseif (str_contains($menuLabel, 'frame')) {
                                $child['image'] = $frameImageDefaults[$childLabel] ?? '';
                            }
                        }
                        return $child;
                    }, $navChildren);
                    $isPopularFramesMenu = str_contains(strtolower((string) ($navItem['label'] ?? '')), 'popular')
                        && str_contains(strtolower((string) ($navItem['label'] ?? '')), 'frame');
                    $isCustomPrintsMenu = str_contains(strtolower((string) ($navItem['label'] ?? '')), 'custom prints');
                    if ($isCustomPrintsMenu) {
                        $existingPrintLabels = array_map(fn ($child) => strtolower(trim((string) ($child['label'] ?? ''))), $navChildren);
                        if (! in_array('acrylic prints', $existingPrintLabels, true)) {
                            $navChildren[] = ['label' => 'Acrylic Prints', 'url' => '/products?q=acrylic', 'image' => $printImageDefaults['acrylic prints']];
                        }
                    }
                    if ($isPopularFramesMenu) {
                        $navChildren = array_map(function ($child) use ($frameImageDefaults) {
                            $childLabel = strtolower(trim((string) ($child['label'] ?? '')));
                            if (isset($frameImageDefaults[$childLabel])) {
                                $child['image'] = $frameImageDefaults[$childLabel];
                            }
                            return $child;
                        }, $navChildren);
                        $popularFrameDefaults = [
                            ['label' => 'Black Frames', 'url' => '/products?q=black', 'image' => '/images/frames/color-black.webp'],
                            ['label' => 'Gold Frames', 'url' => '/products?q=gold', 'image' => '/images/frames/color-gold.webp'],
                            ['label' => 'Natural Frames', 'url' => '/products?q=natural', 'image' => '/images/frames/color-natural.webp'],
                        ];
                        $existingFrameLabels = array_map(fn ($child) => strtolower(trim((string) ($child['label'] ?? ''))), $navChildren);
                        foreach ($popularFrameDefaults as $defaultFrame) {
                            if (! in_array(strtolower($defaultFrame['label']), $existingFrameLabels, true)) {
                                $navChildren[] = $defaultFrame;
                            }
                        }
                    }
                @endphp
                <details class="pf-nav-dropdown">
                    <summary>{{ $navItem['label'] ?? '' }} <span class="pf-nav-caret" aria-hidden="true"></span></summary>
                    @php
                        $hasImageMenu = count($navChildren) > 1 && collect($navChildren)->every(fn ($child) => !empty($child['image']));
                    @endphp
                    <div class="pf-nav-panel {{ $isPopularFramesMenu ? 'pf-popular-image-panel' : ($hasImageMenu ? 'pf-image-menu-panel' : 'pf-resources-panel') }}">
                        @if($isPopularFramesMenu)
                            @foreach($navChildren as $child)
                                <a href="{{ $menuHref($child['url'] ?? '/') }}" class="pf-popular-image-card">
                                    <img src="{{ \App\Support\PublicAssetUrl::image($child['image'], 480, 86) ?? $child['image'] }}" alt="" loading="lazy" decoding="async">
                                    <span>{{ $child['label'] ?? '' }}</span>
                                </a>
                            @endforeach
                            <a href="{{ $menuHref($navItem['url'] ?? '/products') }}" class="pf-popular-shop-all"><span>Shop<br>All</span></a>
                        @elseif($hasImageMenu)
                            <a href="{{ $menuHref($navChildren[0]['url'] ?? '/') }}" class="pf-image-menu-feature">
                                <img src="{{ \App\Support\PublicAssetUrl::image($navChildren[0]['image'], 1000, 86) ?? $navChildren[0]['image'] }}" alt="" loading="lazy" decoding="async">
                                <span>{{ $navChildren[0]['label'] ?? '' }}</span>
                            </a>
                            <div class="pf-image-menu-grid">
                                @foreach(array_slice($navChildren, 1) as $child)
                                    <a href="{{ $menuHref($child['url'] ?? '/') }}" class="pf-image-menu-card">
                                        <img src="{{ \App\Support\PublicAssetUrl::image($child['image'], 560, 86) ?? $child['image'] }}" alt="" loading="lazy" decoding="async">
                                        <span>{{ $child['label'] ?? '' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            @foreach($navChildren as $child)
                                <a href="{{ $menuHref($child['url'] ?? '/') }}" class="{{ !empty($child['image']) ? 'pf-nav-image-link' : '' }}">
                                    @if(!empty($child['image']))<img src="{{ \App\Support\PublicAssetUrl::image($child['image'], 360, 82) ?? $child['image'] }}" alt="" loading="lazy" decoding="async">@endif
                                    <span>{{ $child['label'] ?? '' }}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </details>
            @else
                <a @if(($navItem['label'] ?? '') === 'Clearance') class="pf-nav-clearance" @endif href="{{ $menuHref($navItem['url'] ?? '/') }}">{{ $navItem['label'] ?? '' }}</a>
            @endif
        @endforeach
    </nav>
</header>

<script>
    (() => {
        const initFrameNavigationMenus = () => {
            document.querySelectorAll('.pf-header .pf-nav').forEach((nav) => {
                const menus = [...nav.querySelectorAll('.pf-nav-dropdown')];
                let timer;
                const closeOthers = (active = null) => menus.forEach((menu) => {
                    if (menu !== active) menu.open = false;
                });
                const scheduleClose = () => {
                    window.clearTimeout(timer);
                    timer = window.setTimeout(() => closeOthers(), 220);
                };

                menus.forEach((menu) => {
                    menu.addEventListener('pointerenter', (event) => {
                        if (event.pointerType === 'touch') return;
                        window.clearTimeout(timer);
                        timer = window.setTimeout(() => {
                            closeOthers(menu);
                            menu.open = true;
                        }, 90);
                    });
                    menu.addEventListener('pointerleave', (event) => {
                        if (event.pointerType === 'touch') return;
                        scheduleClose();
                    });
                    menu.addEventListener('toggle', () => {
                        if (menu.open) closeOthers(menu);
                    });
                });

                nav.addEventListener('pointerleave', scheduleClose);
                nav.addEventListener('pointerenter', () => window.clearTimeout(timer));
                document.addEventListener('pointerdown', (event) => {
                    if (!nav.contains(event.target)) closeOthers();
                });
                nav.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeOthers();
                        nav.querySelector('summary:focus')?.blur();
                    }
                });
            });

            document.querySelectorAll('[data-typewriter-text]').forEach((element) => {
                const message = element.dataset.typewriterText || '';
                if (!message) return;
                element.textContent = message;
                element.style.minWidth = `${element.getBoundingClientRect().width}px`;
                element.textContent = '';
                element.setAttribute('aria-busy', 'false');
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    element.textContent = message;
                    element.classList.add('pf-typewriter-static');
                    return;
                }

                let position = 0;
                let deleting = false;
                const typeNext = () => {
                    if (!deleting) {
                        position = Math.min(position + 1, message.length);
                        element.textContent = message.slice(0, position);
                        if (position === message.length) {
                            deleting = true;
                            window.setTimeout(typeNext, 1900);
                            return;
                        }
                        window.setTimeout(typeNext, 42);
                        return;
                    }

                    position = Math.max(position - 1, 0);
                    element.textContent = message.slice(0, position);
                    if (position === 0) {
                        deleting = false;
                        window.setTimeout(typeNext, 500);
                        return;
                    }
                    window.setTimeout(typeNext, 22);
                };
                window.setTimeout(typeNext, 450);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFrameNavigationMenus, { once: true });
        } else {
            initFrameNavigationMenus();
        }
    })();
</script>
