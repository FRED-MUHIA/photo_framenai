@extends('layouts.admin')

@section('heading', 'Blog Posts')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.blog-posts.create') }}" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-center text-sm font-bold text-white sm:w-auto">New Blog Post</a>
</div>

<div class="rounded-xl bg-white p-4 shadow-sm sm:p-5">
    <div class="overflow-x-auto">
    <table class="min-w-[44rem] w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                <th class="py-2">Title</th>
                <th>Slug</th>
                <th>Published</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr class="border-b border-slate-100">
                    <td class="py-2 font-semibold">{{ $post->title }}</td>
                    <td>{{ $post->slug }}</td>
                    <td>{{ $post->is_published ? 'Yes' : 'No' }}</td>
                    <td>{{ $post->published_at?->format('d M Y') ?: '-' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="mr-2 text-xs font-bold">Edit</a>
                        <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-bold text-rose-700">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-4 text-slate-500">No blog posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</div>
@endsection
