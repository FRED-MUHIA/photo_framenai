@extends('layouts.storefront')

@section('meta_title', 'Upload Artwork - NaiPrinters')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8" x-data="artworkUploader()">
    <div class="mb-8">
        <h1 class="np-type-page-title">Upload Artwork</h1>
        <p class="mt-2 text-sm text-slate-600">Supported formats: PDF, PNG, JPG, WEBP and AI. Minimum recommended quality: {{ config('shop.min_dpi') }} DPI.</p>
    </div>

    <form method="POST" action="{{ route('artwork.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <label for="artwork" class="mb-3 block text-sm font-bold">Artwork File</label>
        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center" @dragover.prevent @drop.prevent="onDrop($event)">
            <input id="artwork" type="file" name="artwork" class="mx-auto block text-sm" @change="onFileChange" accept=".pdf,.png,.jpg,.jpeg,.webp,.ai" required>
            <p class="mt-3 text-xs text-slate-500">Drag and drop your file here or click to browse.</p>
            <template x-if="previewUrl">
                <div class="mt-5">
                    <img :src="previewUrl" alt="Preview" class="mx-auto h-40 rounded-xl border border-slate-200 object-contain">
                </div>
            </template>
            <template x-if="fileName">
                <p class="mt-2 text-sm font-semibold text-slate-700" x-text="fileName"></p>
            </template>
        </div>
        <button class="mt-6 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white">Upload Artwork</button>
    </form>

    <div class="mt-10">
        <h2 class="np-type-section-title">Recent Uploads</h2>
        <div class="mt-4 grid gap-3">
            @forelse($artworks as $artwork)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="text-sm font-bold">{{ $artwork->original_name }}</p>
                        <p class="text-xs text-slate-500">{{ strtoupper($artwork->extension ?? '-') }} | {{ number_format($artwork->size_bytes / 1024, 1) }} KB</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold">{{ ucfirst($artwork->status) }}</span>
                        <form method="POST" action="{{ route('artwork.destroy', $artwork) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-semibold text-rose-700">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">No uploads yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function artworkUploader() {
        return {
            previewUrl: null,
            fileName: '',
            onDrop(event) {
                const input = document.getElementById('artwork');
                input.files = event.dataTransfer.files;
                this.onFileChange({ target: input });
            },
            onFileChange(event) {
                const file = event.target.files[0];
                if (!file) {
                    return;
                }

                this.fileName = file.name;
                if (file.type.startsWith('image/')) {
                    this.previewUrl = URL.createObjectURL(file);
                } else {
                    this.previewUrl = null;
                }
            },
        }
    }
</script>
@endpush
