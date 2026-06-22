@extends('layouts.storefront')

@section('meta_title', 'About Us - Nai Printers')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">About Nai Printers</h1>
    <p class="mt-4 text-slate-600">Nai Printers is a web-to-print company built to simplify custom print ordering for businesses, creators, and teams. We combine modern ecommerce with production-grade print workflows.</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4"><h3 class="font-bold">Quality</h3><p class="mt-1 text-sm text-slate-600">Color-managed production with strict preflight checks.</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><h3 class="font-bold">Speed</h3><p class="mt-1 text-sm text-slate-600">Fast turnarounds and transparent delivery estimates.</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-4"><h3 class="font-bold">Scale</h3><p class="mt-1 text-sm text-slate-600">Bulk-friendly pricing for growing brands.</p></div>
    </div>
</section>
@endsection
