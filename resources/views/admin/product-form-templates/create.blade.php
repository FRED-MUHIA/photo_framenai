@extends('layouts.admin')

@section('heading', 'Create Product Form')

@section('content')
<form method="POST" action="{{ route('admin.product-form-templates.store') }}" enctype="multipart/form-data" class="space-y-4 rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.product-form-templates.partials.form', [
        'template' => $template,
        'optionGroupsJson' => old('option_groups_json', $optionGroupsJson),
        'quantityPricesJson' => old('quantity_prices_json', $quantityPricesJson),
    ])
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Form</button>
</form>
@endsection
