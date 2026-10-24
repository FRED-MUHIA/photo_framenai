@extends('layouts.storefront')

@section('meta_title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_type', 'article')
@section('meta_image', \App\Support\PublicAssetUrl::resolve($post->cover_image) ?: '')

@push('head')
@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->meta_description ?: $post->excerpt,
        'url' => rtrim(config('seo.site_url'), '/') . route('pages.blog.show', ['post' => $post->slug], false),
        'datePublished' => optional($post->published_at ?: $post->created_at)?->toAtomString(),
        'dateModified' => optional($post->updated_at)?->toAtomString(),
        'image' => \App\Support\PublicAssetUrl::resolve($post->cover_image),
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('app.name', 'Photo Frames'),
        ],
    ];
@endphp
<script type="application/ld+json">
    {!! json_encode(array_filter($articleSchema), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<article class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('pages.blog') }}" class="text-sm font-semibold text-teal-700">&larr; Back to blog</a>
    <h1 class="np-type-page-title mt-2">{{ $post->title }}</h1>
    <p class="mt-2 text-sm text-slate-500">{{ optional($post->published_at ?: $post->created_at)->format('d M Y') }}</p>

    @php
        $coverImageUrl = \App\Support\PublicAssetUrl::resolve($post->cover_image);
    @endphp

    @if($coverImageUrl)
        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <img loading="eager" fetchpriority="high" decoding="async" src="{{ $coverImageUrl }}" alt="{{ $post->title }}" class="h-[340px] w-full object-cover">
        </div>
    @endif

    @if($post->excerpt)
        <p class="mt-6 text-lg text-slate-700">{{ $post->excerpt }}</p>
    @endif

    <div class="prose prose-slate mt-8 max-w-none">
        {!! $post->content !!}
    </div>
</article>
@endsection
