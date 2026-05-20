@extends('layouts.storefront')

@section('meta_title', 'Contact - NaiPrinters')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">Contact Us</h1>
    <p class="mt-3 text-sm text-slate-600">Need support, a bulk quote, or custom print consultation? Reach out below.</p>
    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="font-bold">Direct Channels</h2>
            <p class="mt-3 text-sm text-slate-600">Email: hello@naiprinters.test</p>
            <p class="text-sm text-slate-600">Phone: +254 700 000 000</p>
            <a href="{{ config('shop.whatsapp_link') }}" class="mt-3 inline-flex rounded-lg bg-emerald-500 px-3 py-2 text-xs font-bold text-white">Chat on WhatsApp</a>
        </div>
        <form class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="space-y-3">
                <input type="text" placeholder="Name" class="w-full rounded-xl border-slate-300">
                <input type="email" placeholder="Email" class="w-full rounded-xl border-slate-300">
                <textarea rows="4" placeholder="How can we help?" class="w-full rounded-xl border-slate-300"></textarea>
                <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Send Message</button>
            </div>
        </form>
    </div>
</section>
@endsection
