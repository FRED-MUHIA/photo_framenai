@extends('layouts.admin')

@section('heading', 'Page Builder')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.page-builder.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Create Page</a>
</div>

<div class="rounded-xl bg-white p-5 shadow-sm">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                <th class="py-2">Page</th>
                <th>Slug</th>
                <th>Sections</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $page->name }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ is_array($page->sections) ? count($page->sections) : 0 }}</td>
                    <td>{{ $page->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.page-builder.edit', $page) }}" class="mr-2 text-xs font-bold">Edit</a>
                        <form method="POST" action="{{ route('admin.page-builder.destroy', $page) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-bold text-rose-700">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-slate-500">No pages yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $pages->links() }}</div>
</div>
@endsection
