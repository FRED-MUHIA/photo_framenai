<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', 'Admin - ' . config('app.name'))</title>
    <link rel="icon" href="{{ \App\Support\PublicAssetUrl::resolve($siteSettings->get('favicon_url')) ?: asset('favicon.svg') }}" sizes="any">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-100 text-slate-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
@php
    $adminNavigation = [
        ['label' => 'Overview', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard')],
        ['label' => 'Products', 'route' => 'admin.products.index', 'active' => request()->routeIs('admin.products.*')],
        ['label' => 'Product Forms', 'route' => 'admin.product-form-templates.index', 'active' => request()->routeIs('admin.product-form-templates.*')],
        ['label' => 'Categories', 'route' => 'admin.categories.index', 'active' => request()->routeIs('admin.categories.*')],
        ['label' => 'Orders', 'route' => 'admin.orders.index', 'active' => request()->routeIs('admin.orders.*')],
        ['label' => 'Artworks', 'route' => 'admin.artworks.index', 'active' => request()->routeIs('admin.artworks.*')],
        ['label' => 'Customers', 'route' => 'admin.customers.index', 'active' => request()->routeIs('admin.customers.*')],
        ['label' => 'Coupons', 'route' => 'admin.coupons.index', 'active' => request()->routeIs('admin.coupons.*')],
        ['label' => 'Media Library', 'route' => 'admin.media-library.index', 'active' => request()->routeIs('admin.media-library.*')],
        ['label' => 'Blog Posts', 'route' => 'admin.blog-posts.index', 'active' => request()->routeIs('admin.blog-posts.*')],
        ['label' => 'Page Builder', 'route' => 'admin.page-builder.index', 'active' => request()->routeIs('admin.page-builder.*')],
        ['label' => 'Header, Footer & Menus', 'route' => 'admin.site-settings.edit', 'active' => request()->routeIs('admin.site-settings.*')],
    ];
@endphp
<div class="min-h-screen">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 border-r border-slate-200 bg-white p-6 lg:block">
            <a href="{{ route('admin.dashboard') }}" class="mb-8 inline-flex items-center gap-2 text-lg font-extrabold">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">NP</span>
                Admin
            </a>
            <nav class="space-y-1 text-sm font-semibold text-slate-700">
                @foreach($adminNavigation as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'block rounded-lg px-3 py-2 hover:bg-slate-100',
                            'bg-slate-900 text-white hover:bg-slate-800' => $item['active'],
                        ])
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('home') }}" class="mt-4 block rounded-lg px-3 py-2 text-slate-500 hover:bg-slate-100">View Storefront</a>
            </nav>
        </aside>
        <div class="min-w-0 flex-1">
            <header class="border-b border-slate-200 bg-white px-3 py-3 sm:px-6 lg:px-8" x-data="{ adminMenuOpen: false }">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-lg border border-slate-300 text-slate-700 lg:hidden"
                            aria-label="Open admin menu"
                            :aria-expanded="adminMenuOpen.toString()"
                            @click="adminMenuOpen = ! adminMenuOpen"
                        >
                            <span x-show="! adminMenuOpen" class="space-y-1" aria-hidden="true">
                                <span class="block h-0.5 w-5 bg-current"></span>
                                <span class="block h-0.5 w-5 bg-current"></span>
                                <span class="block h-0.5 w-5 bg-current"></span>
                            </span>
                            <span x-cloak x-show="adminMenuOpen" class="text-xl leading-none" aria-hidden="true">x</span>
                        </button>
                        <h1 class="min-w-0 text-lg font-bold leading-tight sm:text-xl">@yield('heading', 'Admin')</h1>
                    </div>
                    <div class="flex w-full items-center justify-end gap-2 text-sm sm:w-auto sm:gap-3">
                        <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 px-3 py-2 font-semibold leading-tight">My Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg bg-slate-900 px-3 py-2 font-semibold leading-tight text-white">Logout</button>
                        </form>
                    </div>
                </div>
                <nav class="mt-3 grid grid-cols-2 gap-2 text-sm font-semibold text-slate-700 sm:grid-cols-3 lg:hidden" x-cloak x-show="adminMenuOpen" x-transition>
                    @foreach($adminNavigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'rounded-lg border border-slate-200 px-3 py-2 leading-tight hover:bg-slate-100',
                                'border-slate-900 bg-slate-900 text-white hover:bg-slate-800' => $item['active'],
                            ])
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('home') }}" class="rounded-lg border border-slate-200 px-3 py-2 leading-tight text-slate-500 hover:bg-slate-100">View Storefront</a>
                </nav>
            </header>
            @include('partials.flash')
            <main class="min-w-0 px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</div>
<form id="admin-idle-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
    @csrf
</form>
<script>
    (() => {
        const timeoutMs = Math.max(@json((int) config('session.admin_idle_timeout', 60)), 1) * 60 * 1000;
        const storageKey = 'np_admin_last_activity_at';
        const logoutForm = document.getElementById('admin-idle-logout-form');
        let memoryLastActivityAt = Date.now();
        let timeoutId = null;

        const lastActivityAt = () => {
            try {
                return Number(window.localStorage.getItem(storageKey)) || memoryLastActivityAt;
            } catch (error) {
                return memoryLastActivityAt;
            }
        };
        const storeActivityAt = (timestamp) => {
            memoryLastActivityAt = timestamp;

            try {
                window.localStorage.setItem(storageKey, String(timestamp));
            } catch (error) {
                // Keep the in-memory fallback active when browser storage is blocked.
            }
        };
        const submitLogout = () => {
            if (logoutForm?.requestSubmit) {
                logoutForm.requestSubmit();
                return;
            }

            logoutForm?.submit();
        };
        const scheduleLogout = () => {
            window.clearTimeout(timeoutId);

            const remainingMs = timeoutMs - (Date.now() - lastActivityAt());
            timeoutId = window.setTimeout(submitLogout, Math.max(remainingMs, 0));
        };
        const recordActivity = () => {
            storeActivityAt(Date.now());
            scheduleLogout();
        };
        const checkIdleState = () => {
            if ((Date.now() - lastActivityAt()) >= timeoutMs) {
                submitLogout();
                return;
            }

            scheduleLogout();
        };

        ['click', 'keydown', 'mousemove', 'scroll', 'touchstart'].forEach((eventName) => {
            window.addEventListener(eventName, recordActivity, { passive: true });
        });
        window.addEventListener('focus', checkIdleState);
        window.addEventListener('pageshow', checkIdleState);
        window.addEventListener('storage', (event) => {
            if (event.key === storageKey) {
                scheduleLogout();
            }
        });
        document.addEventListener('visibilitychange', () => {
            if (! document.hidden) {
                checkIdleState();
            }
        });

        recordActivity();
    })();
</script>
@stack('scripts')
</body>
</html>
