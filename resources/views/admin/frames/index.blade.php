@extends('layouts.admin')

@section('heading', 'Frame catalogue')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.frames.create') }}" class="bg-slate-900 px-4 py-2 text-sm font-bold text-white">Add frame</a>
</div>
<div class="overflow-x-auto bg-white p-4 shadow-sm">
    <table class="w-full min-w-[48rem] text-left text-sm">
        <thead><tr class="border-b text-xs uppercase text-slate-500"><th class="py-3">Frame</th><th>SKU / finish</th><th>Starting price</th><th>Orientations</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($frames as $frame)
            <tr class="border-b border-slate-100">
                <td class="py-3"><div class="flex items-center gap-3">@if($frame->thumbnail_path)<img src="{{ asset('storage/' . $frame->thumbnail_path) }}" alt="" class="h-12 w-12 object-cover">@endif<strong>{{ $frame->name }}</strong></div></td>
                <td>{{ $frame->sku ?: '—' }} · {{ $frame->material ?: 'Unspecified' }}</td>
                <td>{{ config('shop.currency_symbol') }} {{ number_format((float) $frame->base_price, 2) }}</td>
                <td>{{ collect($frame->orientations)->map(fn ($value) => ucfirst($value))->join(', ') }}</td>
                <td>{{ $frame->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="text-right"><a class="mr-3 font-bold" href="{{ route('admin.frames.edit', $frame) }}">Edit</a><form class="inline" method="POST" action="{{ route('admin.frames.destroy', $frame) }}" onsubmit="return confirm('Delete this frame?')">@csrf @method('DELETE')<button class="font-bold text-rose-700">Delete</button></form></td>
            </tr>
        @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-500">No frames yet. Add uploaded frame designs to make them available to customers.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $frames->links() }}</div>
</div>
@endsection
