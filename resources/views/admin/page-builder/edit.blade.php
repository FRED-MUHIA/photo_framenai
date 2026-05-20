@extends('layouts.admin')

@section('heading', 'Edit Page')

@section('content')
<form method="POST" action="{{ route('admin.page-builder.update', $page) }}" enctype="multipart/form-data" class="space-y-4 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.page-builder.partials.form')
    <div>
        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Changes</button>
    </div>
</form>
@endsection
