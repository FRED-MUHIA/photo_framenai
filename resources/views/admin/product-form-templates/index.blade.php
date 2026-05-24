@extends('layouts.admin')

@section('heading', 'Product Forms')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-slate-600">Create reusable calculator forms for print services, then attach them to products.</p>
    @if(empty($missingMigration))
        <a href="{{ route('admin.product-form-templates.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Create Form</a>
    @endif
</div>

@if(! empty($missingMigration))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Product Forms need a database migration before they can be used. Run <code class="font-bold">php artisan migrate --force</code> on this server.
    </div>
@endif

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Products</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($templates as $template)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-bold">{{ $template->name }}</p>
                        <p class="text-xs text-slate-500">{{ $template->slug }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $template->products_count }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-1 text-xs font-bold {{ $template->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.product-form-templates.edit', $template) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-bold">Edit</a>
                            <form method="POST" action="{{ route('admin.product-form-templates.destroy', $template) }}" onsubmit="return confirm('Delete this form?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-bold text-rose-700">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">No product forms yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $templates->links() }}</div>
@endsection
