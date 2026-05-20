<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', 'NaiPrinters - Online Printing eCommerce')</title>
    <meta name="description" content="@yield('meta_description', 'Customize and order print products online with live pricing, artwork uploads, and fast delivery.')">
    <link rel="canonical" href="{{ url()->current() }}">
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
            "name": "NaiPrinters",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('favicon.ico') }}",
            "sameAs": ["{{ $siteSettings->get('whatsapp_link', config('shop.whatsapp_link')) }}"]
        }
    </script>
</head>
<body style="background:#f4f4f4; color:#111;">
<div class="min-h-screen">
    @include('partials.site-header')

    @include('partials.flash')

    <main>
        @yield('content')
    </main>

    @include('partials.managed-before-footer')

    @include('partials.site-footer')
</div>
@stack('scripts')
</body>
</html>
