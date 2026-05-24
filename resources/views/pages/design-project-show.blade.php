@extends('layouts.storefront')

@section('meta_title', $project['title'] . ' Project - NaiPrinters')
@section('meta_description', $project['summary'])

@section('content')
@php
    $projectImages = collect($project['images'] ?? [])->filter()->values();
@endphp
<section class="bg-[#f6f6f6] py-8 sm:py-10">
    <div class="mx-auto max-w-[1180px] px-4 sm:px-6 lg:px-8">
        <a href="{{ route('pages.design-services') }}#work" class="inline-flex h-10 items-center rounded-full border border-[#d8d8d8] bg-white px-4 text-[13px] font-bold text-[#111] hover:border-[#b10f1e] hover:text-[#b10f1e]">Back to projects</a>

        <article class="mt-6 overflow-hidden rounded-2xl border border-[#dedede] bg-white shadow-sm">
            <div class="grid gap-0 lg:grid-cols-[0.86fr_1.14fr]">
                <div class="p-6 sm:p-8 lg:p-10">
                    <p class="text-[13px] font-black uppercase tracking-wide text-[#b10f1e]">{{ $project['service'] }}</p>
                    <h1 class="mt-4 text-[44px] font-black leading-none tracking-[-0.05em] text-[#111] sm:text-[72px]">{{ $project['title'] }}</h1>

                    <div class="mt-8 grid gap-4 border-y border-[#e0e0e0] py-5 sm:grid-cols-2">
                        <div>
                            <p class="text-[12px] font-bold uppercase tracking-wide text-[#777]">Project Year</p>
                            <p class="mt-1 text-[22px] font-black text-[#111]">{{ $project['year'] }}</p>
                        </div>
                        <div>
                            <p class="text-[12px] font-bold uppercase tracking-wide text-[#777]">Project Type</p>
                            <p class="mt-1 text-[16px] font-black text-[#111]">{{ $project['service'] }}</p>
                        </div>
                    </div>

                    <p class="mt-6 text-[16px] leading-8 text-[#3f3f3f]">{{ $project['summary'] }}</p>
                </div>

                <div class="min-h-[420px] bg-[#ededed]">
                    @if($projectImages->isNotEmpty())
                        <img src="{{ $projectImages->first() }}" alt="{{ $project['title'] }} hero image" class="h-full min-h-[420px] w-full object-cover">
                    @endif
                </div>
            </div>
        </article>
    </div>
</section>

<section class="bg-white py-10">
    <div class="mx-auto grid max-w-[1180px] gap-8 px-4 sm:px-6 lg:grid-cols-[320px_minmax(0,1fr)] lg:px-8">
        <aside class="h-fit rounded-2xl border border-[#dedede] bg-[#fbfbfb] p-6">
            <p class="text-[12px] font-black uppercase tracking-wide text-[#777]">Deliverables</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($project['deliverables'] as $deliverable)
                    <span class="rounded-full border border-[#d6d6d6] bg-white px-3 py-1.5 text-[12px] font-bold text-[#333]">{{ $deliverable }}</span>
                @endforeach
            </div>
        </aside>

        <div>
            <p class="text-[13px] font-black uppercase tracking-wide text-[#b10f1e]">Project Details</p>
            <h2 class="mt-2 text-[34px] font-black leading-tight tracking-[-0.04em] text-[#111]">What we created</h2>
            <p class="mt-5 max-w-3xl text-[16px] leading-8 text-[#444]">{{ $project['details'] }}</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($projectImages->slice(1) as $image)
                    <figure class="overflow-hidden rounded-2xl border border-[#dedede] bg-[#f4f4f4]">
                        <img src="{{ $image }}" alt="{{ $project['title'] }} project image" class="h-[300px] w-full object-cover">
                    </figure>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f6f6f6] py-10">
    <div class="mx-auto max-w-[1180px] px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-[#dedede] bg-white p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <h2 class="text-[30px] font-black leading-tight tracking-[-0.04em] text-[#111]">Start a similar project</h2>
                    <p class="mt-2 max-w-2xl text-[14px] leading-6 text-[#4f4f4f]">Request design support for branding, websites, graphics, brochures, magazines, and print-ready artwork.</p>
                </div>
                <a href="{{ route('pages.design-services') }}#design-request" class="inline-flex h-9 w-fit items-center justify-center rounded-full bg-[#b10f1e] px-4 text-[13px] font-black text-white hover:bg-[#8f0d18]">Book / Request</a>
            </div>
        </div>
    </div>
</section>
@endsection
