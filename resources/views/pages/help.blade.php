@extends('layouts.storefront')

@section('meta_title', 'Help Center - ' . config('app.name'))

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">Help Center</h1>
    <p class="mt-3 text-sm text-slate-600">Answers to common web-to-print questions.</p>
    <div class="mt-6 space-y-3">
        @foreach($faqs as $faq)
            <details class="rounded-xl border border-slate-200 bg-white p-4">
                <summary class="cursor-pointer text-sm font-bold">{{ $faq['question'] }}</summary>
                <p class="mt-2 text-sm text-slate-600">{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </div>
</section>
@endsection
