@extends('layouts.admin')

@section('heading', 'Create Product')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4 rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.products.partials.form', [
        'product' => $product,
        'categories' => $categories,
        'mediaItems' => $mediaItems,
        'formTemplates' => $formTemplates,
        'selectedCategoryIds' => $selectedCategoryIds,
        'selectedMediaIds' => $selectedMediaIds,
        'optionGroupsJson' => old('option_groups_json', $optionGroupsJson),
        'quantityPricesJson' => old('quantity_prices_json', $quantityPricesJson),
    ])
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Product</button>
</form>
@endsection
