@extends('layouts.admin')

@section('heading', 'Artworks')

@section('content')
<form method="GET" class="mb-4 flex items-center gap-2">
    <select name="status" class="rounded-xl border-slate-300 text-sm">
        <option value="">All statuses</option>
        @foreach(['pending','processed','rejected'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Filter</button>
</form>
<div class="rounded-xl bg-white p-5 shadow-sm">
    <table class="w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">File</th><th>User</th><th>Status</th><th>DPI</th><th></th></tr></thead>
        <tbody>
            @foreach($artworks as $artwork)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $artwork->original_name }}</td>
                    <td>{{ $artwork->user?->email ?? 'Guest' }}</td>
                    <td>{{ ucfirst($artwork->status) }}</td>
                    <td>{{ $artwork->estimated_dpi ? number_format($artwork->estimated_dpi, 2) : '-' }}</td>
                    <td><a href="{{ route('admin.artworks.show', $artwork) }}" class="text-xs font-bold">Review</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $artworks->links() }}</div>
</div>
@endsection
