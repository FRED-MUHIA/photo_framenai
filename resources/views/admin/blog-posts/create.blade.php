@extends('layouts.admin')

@section('heading', 'Create Blog Post')

@section('content')
<form method="POST" action="{{ route('admin.blog-posts.store') }}" class="space-y-4 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @include('admin.blog-posts.partials.form')
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Create Post</button>
</form>
@endsection
