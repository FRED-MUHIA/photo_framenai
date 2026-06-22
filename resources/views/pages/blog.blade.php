@extends('layouts.storefront')

@section('meta_title', $cmsPage?->meta_title ?: 'Blog & Resources - Nai Printers')
@section('meta_description', $cmsPage?->meta_description ?: 'Read printing guides, product tips and web-to-print resources.')
@section('canonical_url', rtrim(config('app.url'), '/') . route('pages.blog', [], false) . ($posts->currentPage() > 1 ? '?page=' . $posts->currentPage() : ''))

@push('head')
    @if($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endpush

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="np-type-page-title">Blog & Resources</h1>
    @if($cmsPage?->body)
        <p class="mt-3 text-sm text-slate-600">{!! nl2br(e($cmsPage->body)) !!}</p>
    @endif
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ optional($post->published_at ?: $post->created_at)->format('d M Y') }}</p>
                <h2 class="mt-2 text-lg font-bold">{{ $post->title }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $post->excerpt }}</p>
                <a href="{{ route('pages.blog.show', ['post' => $post->slug]) }}" class="mt-4 inline-block text-xs font-bold text-teal-700">Read more</a>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                No blog posts published yet.
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</section>
@endsection
