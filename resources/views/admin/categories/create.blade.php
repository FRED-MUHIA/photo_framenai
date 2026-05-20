@extends('layouts.admin')

@section('heading', 'Create Category')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-3xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
    @csrf
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Category Name" class="w-full rounded-xl border-slate-300" required>
    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Slug (optional)" class="w-full rounded-xl border-slate-300">
    <textarea name="description" placeholder="Description" class="w-full rounded-xl border-slate-300">{{ old('description') }}</textarea>
    <div class="grid gap-4 sm:grid-cols-3">
        <input type="text" name="icon" value="{{ old('icon') }}" placeholder="Icon" class="rounded-xl border-slate-300">
        <input type="text" name="image" value="{{ old('image') }}" placeholder="Image URL" class="rounded-xl border-slate-300">
        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" placeholder="Sort Order" class="rounded-xl border-slate-300">
    </div>
    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" checked> Active</label>
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Category</button>
</form>
@endsection
