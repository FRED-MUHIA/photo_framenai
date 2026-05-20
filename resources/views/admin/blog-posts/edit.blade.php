@extends('layouts.admin')

@section('heading', 'Edit Blog Post')

@section('content')
<form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" class="space-y-4 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.blog-posts.partials.form')
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Changes</button>
</form>
@endsection
