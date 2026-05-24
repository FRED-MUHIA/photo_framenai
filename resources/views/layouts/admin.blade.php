<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', 'Admin - NaiPrinters')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-100 text-slate-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
<div class="min-h-screen">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 border-r border-slate-200 bg-white p-6 lg:block">
            <a href="{{ route('admin.dashboard') }}" class="mb-8 inline-flex items-center gap-2 text-lg font-extrabold">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">NP</span>
                Admin
            </a>
            <nav class="space-y-1 text-sm font-semibold text-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Overview</a>
                <a href="{{ route('admin.products.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Products</a>
                <a href="{{ route('admin.product-form-templates.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Product Forms</a>
                <a href="{{ route('admin.categories.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Categories</a>
                <a href="{{ route('admin.orders.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Orders</a>
                <a href="{{ route('admin.artworks.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Artworks</a>
                <a href="{{ route('admin.customers.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Customers</a>
                <a href="{{ route('admin.coupons.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Coupons</a>
                <a href="{{ route('admin.media-library.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Media Library</a>
                <a href="{{ route('admin.blog-posts.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Blog Posts</a>
                <a href="{{ route('admin.page-builder.index') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Page Builder</a>
                <a href="{{ route('admin.site-settings.edit') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-100">Header, Footer & Menus</a>
                <a href="{{ route('home') }}" class="mt-4 block rounded-lg px-3 py-2 text-slate-500 hover:bg-slate-100">View Storefront</a>
            </nav>
        </aside>
        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">@yield('heading', 'Admin')</h1>
                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 px-3 py-1.5 font-semibold">My Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg bg-slate-900 px-3 py-1.5 font-semibold text-white">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
            @include('partials.flash')
            <main class="px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
