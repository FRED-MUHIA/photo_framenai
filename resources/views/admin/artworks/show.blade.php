@extends('layouts.admin')

@section('heading', 'Artwork Review')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold">{{ $artwork->original_name }}</h2>
        <p class="text-sm text-slate-500">{{ strtoupper($artwork->mime_type) }} | {{ number_format($artwork->size_bytes / 1024, 1) }} KB</p>
        <div class="mt-4 rounded-xl border border-slate-200 p-4">
            <p class="text-sm"><span class="font-semibold">Status:</span> {{ ucfirst($artwork->status) }}</p>
            <p class="text-sm"><span class="font-semibold">DPI:</span> {{ $artwork->estimated_dpi ? number_format($artwork->estimated_dpi, 2) : '-' }}</p>
            <p class="text-sm"><span class="font-semibold">Dimensions:</span> {{ $artwork->width_px ?? '-' }} x {{ $artwork->height_px ?? '-' }}</p>
            <p class="mt-2 text-sm"><span class="font-semibold">Notes:</span> {{ $artwork->validation_notes ?: 'None' }}</p>
        </div>
    </div>
    <aside class="rounded-xl bg-white p-5 shadow-sm">
        <h3 class="font-bold">Update Artwork</h3>
        <form method="POST" action="{{ route('admin.artworks.status', $artwork) }}" class="mt-3 space-y-3">
            @csrf
            @method('PATCH')
            <select name="status" class="w-full rounded-xl border-slate-300">
                @foreach(['pending','processed','rejected'] as $s)
                    <option value="{{ $s }}" @selected($artwork->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <textarea name="validation_notes" rows="4" class="w-full rounded-xl border-slate-300" placeholder="Review notes">{{ old('validation_notes', $artwork->validation_notes) }}</textarea>
            <button class="w-full rounded-xl bg-slate-900 py-2 text-sm font-bold text-white">Save</button>
        </form>
    </aside>
</div>
@endsection
