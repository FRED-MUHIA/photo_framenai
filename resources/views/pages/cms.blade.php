@extends('layouts.storefront')

@section('meta_title', $cmsPage->meta_title ?: $cmsPage->name . ' - NaiPrinters')
@section('meta_description', $cmsPage->meta_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $cmsPage->body), 160))

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
    @if($cmsPage->slug !== 'home')
        <h1 class="np-type-page-title">{{ $cmsPage->name }}</h1>
    @endif

    @if($cmsPage->body)
        <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 text-slate-700">
            {!! nl2br(e($cmsPage->body)) !!}
        </div>
    @endif

    @php
        $sections = is_array($cmsPage->sections) ? $cmsPage->sections : [];
    @endphp

    @if(! empty($sections))
        <div class="mt-6 space-y-4">
            @foreach($sections as $section)
                @php
                    $component = data_get($section, 'component', 'text');
                    $title = data_get($section, 'title');
                    $subtitle = data_get($section, 'subtitle');
                    $content = data_get($section, 'content');
                    $image = data_get($section, 'image_url');
                    $bgColor = data_get($section, 'bg_color', '#ffffff');
                    $textColor = data_get($section, 'text_color', '#111111');
                    $items = collect(data_get($section, 'items', []))->filter(fn ($item) => is_array($item))->values();
                @endphp

                @if($component === 'hero')
                    <article class="overflow-hidden rounded-2xl border border-slate-200" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        @if($image)
                            <img src="{{ $image }}" alt="{{ $title }}" class="h-[260px] w-full object-cover">
                        @endif
                        <div class="p-6">
                            @if($title)
                                <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                            @endif
                            @if($subtitle)
                                <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                            @endif
                            @if($content)
                                <p class="mt-2 opacity-90">{{ $content }}</p>
                            @endif
                            @if(data_get($section, 'button_label') && data_get($section, 'button_url'))
                                <a href="{{ data_get($section, 'button_url') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">{{ data_get($section, 'button_label') }}</a>
                            @endif
                        </div>
                    </article>
                @elseif($component === 'cta')
                    <article class="rounded-2xl border border-slate-200 p-6" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        @if($title)
                            <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                        @endif
                        @if($subtitle)
                            <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                        @endif
                        @if($content)
                            <p class="mt-2 opacity-90">{{ $content }}</p>
                        @endif
                        @if(data_get($section, 'button_label') && data_get($section, 'button_url'))
                            <a href="{{ data_get($section, 'button_url') }}" class="mt-4 inline-flex rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-900">{{ data_get($section, 'button_label') }}</a>
                        @endif
                    </article>
                @elseif($component === 'cards')
                    <article class="rounded-2xl border border-slate-200 p-6" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        @if($title)
                            <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                        @endif
                        @if($subtitle)
                            <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                        @endif
                        @if($content)
                            <p class="mt-2 opacity-90">{{ $content }}</p>
                        @endif
                        @if($items->isNotEmpty())
                            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($items as $item)
                                    <a href="{{ data_get($item, 'link_url', '#') }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white text-slate-900">
                                        @if(data_get($item, 'image_url'))
                                            <img src="{{ data_get($item, 'image_url') }}" alt="{{ data_get($item, 'title') }}" class="h-40 w-full object-cover">
                                        @endif
                                        <div class="p-4">
                                            <h3 class="text-lg font-bold">{{ data_get($item, 'title') }}</h3>
                                            @if(data_get($item, 'subtitle'))
                                                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ data_get($item, 'subtitle') }}</p>
                                            @endif
                                            @if(data_get($item, 'content'))
                                                <p class="mt-2 text-sm text-slate-600">{{ data_get($item, 'content') }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @elseif($component === 'faq')
                    <article class="rounded-2xl border border-slate-200 p-6" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        @if($title)
                            <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                        @endif
                        @if($content)
                            <p class="mt-2 opacity-90">{{ $content }}</p>
                        @endif
                        @if($items->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach($items as $item)
                                    <details class="rounded-lg border border-slate-200 bg-white p-4 text-slate-900">
                                        <summary class="cursor-pointer text-sm font-bold">{{ data_get($item, 'title') }}</summary>
                                        <p class="mt-2 text-sm text-slate-600">{{ data_get($item, 'content') }}</p>
                                    </details>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @elseif($component === 'gallery')
                    <article class="rounded-2xl border border-slate-200 p-6" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        @if($title)
                            <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                        @endif
                        @if($subtitle)
                            <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                        @endif
                        @if($items->isNotEmpty())
                            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach($items as $item)
                                    @if(data_get($item, 'image_url'))
                                        <a href="{{ data_get($item, 'link_url', '#') }}" class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                                            <img src="{{ data_get($item, 'image_url') }}" alt="{{ data_get($item, 'title') }}" class="h-40 w-full object-cover">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </article>
                @else
                    <article class="rounded-2xl border border-slate-200 p-6" style="background: {{ $bgColor }}; color: {{ $textColor }};">
                        <div class="grid gap-4 md:grid-cols-[1fr_280px] md:items-start">
                            <div>
                                @if($title)
                                    <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                                @endif
                                @if($subtitle)
                                    <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                                @endif
                                @if($content)
                                    <p class="mt-2 whitespace-pre-line opacity-90">{{ $content }}</p>
                                @endif
                                @if(data_get($section, 'button_label') && data_get($section, 'button_url'))
                                    <a href="{{ data_get($section, 'button_url') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">{{ data_get($section, 'button_label') }}</a>
                                @endif
                            </div>
                            @if($image)
                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    <img src="{{ $image }}" alt="{{ $title }}" class="h-44 w-full object-cover">
                                </div>
                            @endif
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    @endif
</section>
@endsection
