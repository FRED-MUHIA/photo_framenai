@extends('layouts.storefront')

@section('meta_title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)

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
            <img src="{{ $coverImageUrl }}" alt="{{ $post->title }}" class="h-[340px] w-full object-cover">
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
