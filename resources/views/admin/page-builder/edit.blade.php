@extends('layouts.admin')

@section('heading', 'Edit Page')

@section('content')
@if($page->slug === 'home')
    <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
        Edit the homepage sections below, including hero slides, section headings, featured cards, frame colors, print categories, and value statements. To edit the storefront header, navigation, or footer, open <a class="font-bold underline" href="{{ route('admin.site-settings.edit') }}">Site Settings</a>.
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
