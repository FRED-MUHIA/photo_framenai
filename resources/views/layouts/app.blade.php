<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nai Printers') }}</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" href="{{ \App\Support\PublicAssetUrl::resolve($siteSettings->get('favicon_url')) ?: asset('favicon.svg') }}" sizes="any">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('partials.canonical-host-guard')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body style="background:#f4f4f4; color:#111;">
    <div class="min-h-screen">
        @include('partials.site-header')

        @include('partials.flash')

        @if (isset($header))
            <header class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-[#d9d9d9] bg-white px-5 py-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>

        @include('partials.site-footer')
    </div>
    @stack('scripts')
</body>
</html>
