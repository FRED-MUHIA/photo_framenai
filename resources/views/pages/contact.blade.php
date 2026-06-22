@extends('layouts.storefront')

@section('meta_title', $cmsPage?->meta_title ?: 'Contact - ' . config('app.name'))
@section('meta_description', $cmsPage?->meta_description ?: 'Contact ' . config('app.name') . ' for support, bulk quotes, and custom print consultation.')

@section('content')
@php
    $contactSections = collect($contactSections ?? []);
    $introSection = $contactSections->firstWhere('component', 'contact_intro') ?: [];
    $channelsSection = $contactSections->firstWhere('component', 'contact_channels') ?: [];
    $formSection = $contactSections->firstWhere('component', 'contact_form') ?: [];
    $formItems = collect(data_get($formSection, 'items', []))->filter(fn ($item) => is_array($item))->values();
    $channelLines = collect(preg_split('/\r\n|\r|\n/', (string) data_get($channelsSection, 'content', '')))
        ->map(fn ($line) => trim($line))
        ->filter()
        ->values();
    $introContent = $cmsPage?->body ?: data_get($introSection, 'content', 'Need support, a bulk quote, or custom print consultation? Reach out below.');
@endphp

<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">{{ data_get($introSection, 'title', 'Contact Us') }}</h1>
    @if($introContent)
        <p class="mt-3 text-sm text-slate-600">{{ $introContent }}</p>
    @endif
    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="font-bold">{{ data_get($channelsSection, 'title', 'Direct Channels') }}</h2>
            <div class="mt-3 space-y-1">
                @foreach($channelLines as $line)
                    <p class="text-sm text-slate-600">{{ $line }}</p>
                @endforeach
            </div>
            <a href="{{ data_get($channelsSection, 'button_url') ?: config('shop.whatsapp_link') }}" class="mt-3 inline-flex rounded-lg bg-emerald-500 px-3 py-2 text-xs font-bold text-white">
                {{ data_get($channelsSection, 'button_label', 'Chat on WhatsApp') }}
            </a>
        </div>
        <form class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="space-y-3">
                <input type="text" placeholder="{{ data_get($formItems->get(0), 'title', 'Name') }}" class="w-full rounded-xl border-slate-300">
                <input type="email" placeholder="{{ data_get($formItems->get(1), 'title', 'Email') }}" class="w-full rounded-xl border-slate-300">
                <textarea rows="4" placeholder="{{ data_get($formItems->get(2), 'title', 'How can we help?') }}" class="w-full rounded-xl border-slate-300"></textarea>
                <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">{{ data_get($formSection, 'button_label', 'Send Message') }}</button>
            </div>
        </form>
    </div>
</section>
@endsection
