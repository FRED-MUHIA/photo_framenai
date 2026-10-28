@extends('layouts.admin')

@section('heading', $page->slug === 'home' ? 'Edit Homepage' : 'Edit Page')

@section('content')
@if(session('status'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800" role="status">
        {{ session('status') }}
    </div>
@endif
@if($page->slug === 'home')
    <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
        The homepage layout is fixed. Edit its text, images, featured cards, frame colors, print categories, and value statements here. Header, navigation, and footer settings are in <a class="font-bold underline" href="{{ route('admin.site-settings.edit') }}">Site Settings</a>.
    </div>
@endif
<form method="POST" action="{{ route('admin.page-builder.update', $page) }}" enctype="multipart/form-data" class="space-y-4 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.page-builder.partials.form')
    <div>
        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Changes</button>
    </div>
</form>
@endsection
