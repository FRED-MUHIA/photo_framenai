@extends('layouts.admin')

@section('heading', 'Artworks')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
    <select name="status" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm sm:flex-none">
        <option value="">All statuses</option>
        @foreach(['pending','processed','rejected'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Filter</button>
</form>
<div class="rounded-xl bg-white p-4 shadow-sm sm:p-5">
    <div class="overflow-x-auto">
    <table class="min-w-[48rem] w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">File</th><th>User</th><th>Status</th><th>DPI</th><th>Usage</th><th></th></tr></thead>
        <tbody>
            @foreach($artworks as $artwork)
                @php
                    $isInUse = filled($artwork->order_item_id) || $artwork->orderItem || $artwork->order_items_count > 0;
                @endphp
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $artwork->original_name }}</td>
                    <td>{{ $artwork->user?->email ?? 'Guest' }}</td>
                    <td>{{ ucfirst($artwork->status) }}</td>
                    <td>{{ $artwork->estimated_dpi ? number_format($artwork->estimated_dpi, 2) : '-' }}</td>
                    <td>
                        @if($isInUse)
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">In use</span>
                        @else
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">Unused</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.artworks.show', $artwork) }}" class="text-xs font-bold">Review</a>
                            <form method="POST" action="{{ route('admin.artworks.destroy', $artwork) }}" onsubmit='return confirm(@js('Delete artwork ' . $artwork->original_name . '? This cannot be undone.'));'>
                                @csrf
                                @method('DELETE')
                                <button class="text-xs font-bold text-rose-700">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $artworks->links() }}</div>
</div>
@endsection
