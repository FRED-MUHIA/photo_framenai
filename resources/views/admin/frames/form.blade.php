@extends('layouts.admin')

@section('heading', $frame->exists ? 'Edit frame' : 'Add frame')

@section('content')
@php
    $sizesText = collect($frame->sizes ?? [])->map(fn ($size) => implode('|', [$size['name'] ?? '', $size['width_cm'] ?? '', $size['height_cm'] ?? '', $size['price'] ?? 0]))->join("\n");
    $matsJson = json_encode($frame->mats ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $accessoriesJson = json_encode($frame->accessories ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
@endphp
<form method="POST" enctype="multipart/form-data" action="{{ $frame->exists ? route('admin.frames.update', $frame) : route('admin.frames.store') }}" class="space-y-5 bg-white p-5 shadow-sm" x-data="{ assetType: @js(old('asset_type', $frame->asset_type ?: 'overlay')) }">
    @csrf @if($frame->exists) @method('PUT') @endif
    <div class="grid gap-4 md:grid-cols-2">
        <label class="text-sm font-bold">Frame name<input class="mt-1 w-full border-slate-300" name="name" required value="{{ old('name', $frame->name) }}"></label>
        <label class="text-sm font-bold">SKU<input class="mt-1 w-full border-slate-300" name="sku" value="{{ old('sku', $frame->sku) }}"></label>
        <label class="text-sm font-bold">Slug<input class="mt-1 w-full border-slate-300" name="slug" value="{{ old('slug', $frame->slug) }}" placeholder="Generated from name"></label>
        <label class="text-sm font-bold">Material / finish<input class="mt-1 w-full border-slate-300" name="material" value="{{ old('material', $frame->material) }}"></label>
        <label class="text-sm font-bold">Asset type<select name="asset_type" x-model="assetType" class="mt-1 w-full border-slate-300"><option value="overlay">Transparent frame overlay</option><option value="texture">Frame texture</option></select></label>
        <label class="text-sm font-bold">Base price ({{ config('shop.currency') }})<input type="number" min="0" step="0.01" name="base_price" required value="{{ old('base_price', $frame->base_price ?? 0) }}" class="mt-1 w-full border-slate-300"></label>
        <label class="text-sm font-bold">Minimum profile width (cm)<input type="number" min="0.5" max="30" step="0.1" name="min_width_cm" required value="{{ old('min_width_cm', $frame->min_width_cm ?? 2) }}" class="mt-1 w-full border-slate-300"></label>
        <label class="text-sm font-bold">Maximum profile width (cm)<input type="number" min="0.5" max="30" step="0.1" name="max_width_cm" required value="{{ old('max_width_cm', $frame->max_width_cm ?? 12) }}" class="mt-1 w-full border-slate-300"></label>
        <label class="text-sm font-bold">Price per extra cm<input type="number" min="0" step="0.01" name="width_price_per_cm" value="{{ old('width_price_per_cm', $frame->width_price_per_cm ?? 0) }}" class="mt-1 w-full border-slate-300"></label>
        <label class="text-sm font-bold">Inventory (blank = unlimited)<input type="number" min="0" name="inventory" value="{{ old('inventory', $frame->inventory) }}" class="mt-1 w-full border-slate-300"></label>
        <label class="text-sm font-bold">Display order<input type="number" min="0" name="sort_order" value="{{ old('sort_order', $frame->sort_order ?? 0) }}" class="mt-1 w-full border-slate-300"></label>
    </div>
    <label class="block text-sm font-bold">Description<textarea name="description" rows="3" class="mt-1 w-full border-slate-300">{{ old('description', $frame->description) }}</textarea></label>
    <fieldset><legend class="mb-2 text-sm font-bold">Supported orientations</legend><div class="flex gap-6 text-sm"><label><input type="checkbox" name="orientations[]" value="portrait" @checked(in_array('portrait', old('orientations', $frame->orientations ?? ['portrait', 'landscape'])))> Portrait</label><label><input type="checkbox" name="orientations[]" value="landscape" @checked(in_array('landscape', old('orientations', $frame->orientations ?? ['portrait', 'landscape'])))> Landscape</label></div></fieldset>
    <div class="grid gap-4 md:grid-cols-2">
        @foreach([['thumbnail','Gallery thumbnail','thumbnail_path','image/*'],['overlay','Portrait transparent overlay','overlay_path','image/png,image/webp'],['landscape_overlay','Landscape transparent overlay (optional)','landscape_overlay_path','image/png,image/webp'],['texture','Frame texture','texture_path','image/*']] as [$field,$label,$attribute,$accept])
            <label class="text-sm font-bold">{{ $label }}<input type="file" name="{{ $field }}" accept="{{ $accept }}" class="mt-1 block w-full border border-slate-300 p-2 text-sm">@if($frame->{$attribute})<img class="mt-2 h-20 object-contain" src="{{ asset('storage/' . $frame->{$attribute}) }}" alt="Current {{ strtolower($label) }}">@endif</label>
        @endforeach
    </div>
    <label class="block text-sm font-bold">Print sizes (one per line: Name | width cm | height cm | price adjustment)<textarea name="sizes_text" rows="5" class="mt-1 w-full border-slate-300 font-mono text-sm" placeholder="A4 | 21 | 29.7 | 0&#10;30 × 40 cm | 30 | 40 | 600">{{ old('sizes_text', $sizesText) }}</textarea></label>
    <label class="block text-sm font-bold">Optional mat colours and pricing (JSON)<textarea name="mats_json" rows="4" class="mt-1 w-full border-slate-300 font-mono text-sm" placeholder='[{"name":"Ivory","color":"#f2eee5","price":500}]'>{{ old('mats_json', $matsJson ?: '[]') }}</textarea><span class="mt-1 block font-normal text-slate-500">Use an array of options with a name, six-digit hex colour, and optional price.</span></label>
    <label class="block text-sm font-bold">Accessories JSON (optional)<textarea name="accessories_json" rows="4" class="mt-1 w-full border-slate-300 font-mono text-sm">{{ old('accessories_json', $accessoriesJson ?: '[]') }}</textarea></label>
    <label class="inline-flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $frame->is_active))> Publish in customer gallery</label>
    @if($errors->any())<div class="border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800">{{ $errors->first() }}</div>@endif
    <div class="flex gap-3"><button class="bg-slate-900 px-5 py-2.5 font-bold text-white">{{ $frame->exists ? 'Save frame' : 'Create frame' }}</button><a href="{{ route('admin.frames.index') }}" class="border border-slate-300 px-5 py-2.5 font-bold">Cancel</a></div>
</form>
@endsection
