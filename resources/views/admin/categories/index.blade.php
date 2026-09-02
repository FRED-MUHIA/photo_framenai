@extends('layouts.admin')

@section('heading', 'Categories')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.categories.create') }}" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-center text-sm font-bold text-white sm:w-auto">Add Category</a>
</div>
<div class="rounded-xl bg-white p-4 shadow-sm sm:p-5">
    <div class="overflow-x-auto">
    <table class="min-w-[42rem] w-full text-left text-sm">
        <thead><tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500"><th class="py-2">Name</th><th>Slug</th><th>Products</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($categories as $category)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="mr-2 text-xs font-bold">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-bold text-rose-700">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
