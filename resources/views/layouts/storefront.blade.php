<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $storefrontBreadcrumbs = \App\Support\StorefrontBreadcrumbs::forCurrentRequest(request());
        $metaTitle = trim($__env->yieldContent('meta_title'))
            ?: config('app.name', 'Nai Printers') . ' | ' . config('app.tagline');
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: config('app.tagline');
        $metaRobots = trim($__env->yieldContent('meta_robots')) ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        $configuredRoot = rtrim((string) config('app.url'), '/');
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
    <meta property="og:site_name" content="{{ config('app.name', 'Nai Printers') }}">
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
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": @json(config('app.name', 'Nai Printers')),
            "url": "{{ rtrim(config('app.url'), '/') }}/",
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
    @include('partials.site-header')

    @include('partials.flash')

    @include('partials.breadcrumbs', ['items' => $storefrontBreadcrumbs])

    <main>
        @yield('content')
    </main>

    @include('partials.managed-before-footer')

    @include('partials.site-footer')
</div>
@stack('scripts')
</body>
</html>
