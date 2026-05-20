@extends('layouts.admin')

@section('heading', 'Media Library')

@section('content')
<div class="grid gap-4 lg:grid-cols-[340px_1fr]">
    <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">Upload Media</h2>
        <form method="POST" action="{{ route('admin.media-library.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="text" name="title" class="w-full rounded-xl border-slate-300" placeholder="Title (optional)">
            <input type="text" name="alt_text" class="w-full rounded-xl border-slate-300" placeholder="Alt text (optional)">
            <input type="file" name="files[]" multiple accept="image/*" class="w-full rounded-xl border-slate-300">
            <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Upload Images</button>
        </form>

        <div class="border-t border-slate-200 pt-4">
            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">Create Media Link</h3>
            <form method="POST" action="{{ route('admin.media-library.store') }}" class="mt-2 space-y-3">
                @csrf
                <input type="text" name="title" class="w-full rounded-xl border-slate-300" placeholder="Title">
                <input type="url" name="link_url" class="w-full rounded-xl border-slate-300" placeholder="https://...">
                <input type="text" name="alt_text" class="w-full rounded-xl border-slate-300" placeholder="Alt text (optional)">
                <button class="rounded-xl bg-slate-700 px-4 py-2 text-sm font-bold text-white">Add Link</button>
            </form>
        </div>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">Library Items</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($mediaItems as $item)
                <article class="overflow-hidden rounded-xl border border-slate-200">
                    <div class="h-40 bg-slate-100">
                        @if($item->url)
                            <img src="{{ $item->url }}" alt="{{ $item->alt_text ?: $item->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-xs text-slate-400">No preview</div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="truncate text-sm font-semibold">{{ $item->title ?: 'Untitled media' }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ strtoupper($item->type) }}</p>
                        @if($item->url)
                            <div class="mt-2 rounded border border-slate-200 bg-slate-50 p-2">
                                <input
                                    id="media-url-{{ $item->id }}"
                                    type="text"
                                    readonly
                                    value="{{ $item->url }}"
                                    class="w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-700"
                                >
                                <div class="mt-2 flex items-center gap-3">
                                    <button
                                        type="button"
                                        class="rounded bg-slate-800 px-2 py-1 text-[11px] font-bold text-white"
                                        onclick='copyMediaLinkById("media-url-{{ $item->id }}")'
                                    >
                                        Copy Link
                                    </button>
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener" class="text-[11px] font-bold text-slate-700">Open</a>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.media-library.destroy', $item) }}" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-bold text-rose-700">Delete</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500 sm:col-span-2 xl:col-span-3">No media items yet.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $mediaItems->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function copyMediaLinkById(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const url = input.value || '';
        if (!url) return;

        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(url);
                input.focus();
                input.select();
                return;
            }
        } catch (e) {}

        input.focus();
        input.select();
        window.prompt('Copy this media link:', url);
    }
</script>
@endpush
