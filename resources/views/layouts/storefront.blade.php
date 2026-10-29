<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $isFrameCustomizer = request()->routeIs('frames.customize*');
        $storefrontLogo = \App\Support\PublicAssetUrl::resolve($siteSettings->get('header_logo_url'));
        $storefrontBrand = $siteSettings->get('header_brand_label', $siteSettings->get('site_name', config('app.name', 'Photo Frames')));
        $storefrontBreadcrumbs = \App\Support\StorefrontBreadcrumbs::forCurrentRequest(request());
        $metaTitle = trim($__env->yieldContent('meta_title'))
            ?: config('app.name', 'Photo Frames') . ' | ' . config('app.tagline');
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: config('app.tagline');
        $metaRobots = trim($__env->yieldContent('meta_robots')) ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        $configuredRoot = rtrim((string) config('seo.site_url'), '/');
        $requestPath = request()->getPathInfo();
        $defaultCanonicalUrl = $configuredRoot . ($requestPath === '/' ? '/' : $requestPath);
        $canonicalUrl = trim($__env->yieldContent('canonical_url')) ?: $defaultCanonicalUrl;
        $metaType = trim($__env->yieldContent('meta_type')) ?: 'website';
        $metaImage = trim($__env->yieldContent('meta_image'));
        $faviconUrl = \App\Support\PublicAssetUrl::resolve($siteSettings->get('favicon_url'))
            ?: $configuredRoot . '/favicon.svg';
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    @if(trim($__env->yieldContent('meta_keywords')) !== '')
        <meta name="keywords" content="{{ trim($__env->yieldContent('meta_keywords')) }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Photo Frames') }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($metaImage !== '')
        <meta property="og:image" content="{{ $metaImage }}">
    @endif
    <meta name="twitter:card" content="{{ $metaImage !== '' ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($metaImage !== '')
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="preconnect" href="https://images.pexels.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Fredoka+One&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('partials.canonical-host-guard')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frames-home.css') }}?v=46">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @if($isFrameCustomizer)
        <style>
            .fc-minimal-header{min-height:62px;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;gap:18px;background:#fff;border-bottom:1px solid #e7e4df}
            .fc-minimal-header .pf-logo{font-size:25px}
            .fc-minimal-header .fc-home-link{color:#b51222;font:600 14px/1.2 Inter,Arial,sans-serif;text-decoration:none}
            .fc-minimal-header .fc-home-link:hover{text-decoration:underline}
            @media(max-width:600px){.fc-minimal-header{min-height:54px;padding:8px 14px}.fc-minimal-header .pf-logo{font-size:21px}}
        </style>
    @endif
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": @json(config('app.name', 'Photo Frames')),
            "url": "{{ rtrim(config('seo.site_url'), '/') }}/",
            "logo": "{{ $faviconUrl }}",
            "sameAs": ["{{ $siteSettings->get('whatsapp_link', config('shop.whatsapp_link')) }}"]
        }
    </script>
    @if(count($storefrontBreadcrumbs) > 1)
        <script type="application/ld+json">
            {!! json_encode(\App\Support\StorefrontBreadcrumbs::toSchema($storefrontBreadcrumbs), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
</head>
<body style="background:#f4f4f4; color:#111;">
<div class="min-h-screen">
    @if($isFrameCustomizer)
        <header class="fc-minimal-header">
            <a class="pf-logo" href="{{ route('home') }}" aria-label="{{ $storefrontBrand }} home">@if($storefrontLogo)<img src="{{ $storefrontLogo }}" alt="{{ $storefrontBrand }}" class="pf-header-logo-image">@else<span class="pf-logo-mark" aria-hidden="true"></span>{{ $storefrontBrand }}@endif</a>
            <a class="fc-home-link" href="{{ route('home') }}">{{ $siteSettings->get('header_home_link_label', '← Back to homepage') }}</a>
        </header>
    @else
        @include('partials.frames-header')
    @endif

    @include('partials.flash')

    @unless($isFrameCustomizer)
        @include('partials.breadcrumbs', ['items' => $storefrontBreadcrumbs])
    @endunless

    <main>
        @yield('content')
    </main>

    @unless($isFrameCustomizer)
        @include('partials.frames-footer')
    @endunless
</div>
@stack('scripts')
</body>
</html>
