<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-bold">Categories (select one or more)</label>
        @php
            $oldCategoryIds = old('category_ids', $selectedCategoryIds ?? []);
        @endphp
        <select name="category_ids[]" class="w-full rounded-xl border-slate-300" multiple size="5" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(in_array($category->id, $oldCategoryIds))>{{ $category->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Hold Ctrl/Cmd to select multiple categories.</p>
    </div>
    @if(isset($formTemplates))
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-bold">Calculator Form</label>
        @php
            $selectedFormTemplateId = old('product_form_template_id', $product->product_form_template_id);
        @endphp
        <select name="product_form_template_id" class="w-full rounded-xl border-slate-300">
            <option value="" @selected(blank($selectedFormTemplateId))>No calculator form / not necessary</option>
            @foreach(($formTemplates ?? collect()) as $template)
                <option value="{{ $template->id }}" @selected((string) $selectedFormTemplateId === (string) $template->id)>
                    {{ $template->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Choose a saved form only when this product needs calculator options. Select "No calculator form" to remove copied options and use no form.</p>
    </div>
    @endif
    <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Product Name" class="rounded-xl border-slate-300" required>
    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Slug (optional)" class="rounded-xl border-slate-300">
    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="SKU" class="rounded-xl border-slate-300">
    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" placeholder="Base Price" class="rounded-xl border-slate-300" required>
    <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity ?: 100) }}" placeholder="Min Quantity" class="rounded-xl border-slate-300" required>
    <input type="number" name="base_delivery_days" value="{{ old('base_delivery_days', $product->base_delivery_days ?: 3) }}" placeholder="Base Delivery Days" class="rounded-xl border-slate-300" required>
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-bold">Product Thumbnail</label>
        @php
            $thumbnailValue = old('thumbnail', $product->thumbnail);
            $thumbnailPreview = \App\Support\PublicAssetUrl::resolve($thumbnailValue);
        @endphp
        @if($thumbnailPreview)
            <div class="mb-3 h-32 w-32 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                <img src="{{ $thumbnailPreview }}" alt="{{ $product->name ?: 'Product thumbnail' }}" class="h-full w-full object-cover">
            </div>
        @endif
        <div class="grid gap-3 sm:grid-cols-2">
            <input type="file" name="thumbnail_file" accept="image/*" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
            <input type="text" name="thumbnail" value="{{ $thumbnailValue }}" placeholder="Or paste Thumbnail URL" class="rounded-xl border-slate-300">
        </div>
        <p class="mt-1 text-xs text-slate-500">Upload an image, or paste a URL. Uploaded image will be used as the product thumbnail.</p>
    </div>
</div>
<input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" placeholder="Short Description" class="w-full rounded-xl border-slate-300">
<textarea name="description" placeholder="Description" class="w-full rounded-xl border-slate-300">{{ old('description', $product->description) }}</textarea>

<div class="grid gap-4 sm:grid-cols-2">
    <input type="text" name="metadata[meta_title]" value="{{ old('metadata.meta_title', data_get($product->metadata, 'meta_title')) }}" placeholder="Meta Title" class="rounded-xl border-slate-300">
    <input type="text" name="metadata[meta_description]" value="{{ old('metadata.meta_description', data_get($product->metadata, 'meta_description')) }}" placeholder="Meta Description" class="rounded-xl border-slate-300">
    <input type="text" name="metadata[seo_keywords]" value="{{ old('metadata.seo_keywords', data_get($product->metadata, 'seo_keywords')) }}" placeholder="SEO Keywords (comma separated)" class="rounded-xl border-slate-300 sm:col-span-2">
    <input type="text" name="metadata[configurator_intro]" value="{{ old('metadata.configurator_intro', data_get($product->metadata, 'configurator_intro')) }}" placeholder="Configurator intro text e.g. Choose size, paper, quantity..." class="rounded-xl border-slate-300 sm:col-span-2">
</div>

<div class="rounded-xl border border-slate-200 p-4">
    <label class="mb-2 block text-sm font-bold">Media Library (select multiple product images)</label>
    @php
        $oldMediaIds = old('media_ids', $selectedMediaIds ?? []);
    @endphp
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($mediaItems as $media)
            <label class="rounded-lg border border-slate-200 p-2 text-xs">
                <input type="checkbox" name="media_ids[]" value="{{ $media->id }}" @checked(in_array($media->id, $oldMediaIds))>
                <div class="mt-2 h-24 overflow-hidden rounded bg-slate-100">
                    @if($media->url)
                        <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $media->title }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <p class="mt-1 truncate font-semibold">{{ $media->title ?: 'Media #' . $media->id }}</p>
            </label>
        @empty
            <p class="text-xs text-slate-500">No media found. Add files in Admin &gt; Media Library.</p>
        @endforelse
    </div>
</div>

<input type="hidden" name="option_groups_json" value="{{ $optionGroupsJson }}">
<input type="hidden" name="quantity_prices_json" value="{{ $quantityPricesJson }}">

<div class="flex gap-4">
    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
</div>
