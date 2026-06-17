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
            $alternativeImages = [
                1 => old('metadata.alternative_image_1', data_get($product->metadata, 'alternative_image_1')),
                2 => old('metadata.alternative_image_2', data_get($product->metadata, 'alternative_image_2')),
            ];
        @endphp
        @if($thumbnailPreview)
            <div class="mb-3 h-32 w-32 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                <img loading="lazy" decoding="async" src="{{ $thumbnailPreview }}" alt="{{ $product->name ?: 'Product thumbnail' }}" class="h-full w-full object-cover">
            </div>
        @endif
        <div class="grid gap-3 sm:grid-cols-2">
            <input type="file" name="thumbnail_file" accept="image/*" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
            <input type="text" name="thumbnail" value="{{ $thumbnailValue }}" placeholder="Or paste Thumbnail URL" class="rounded-xl border-slate-300">
        </div>
        <p class="mt-1 text-xs text-slate-500">Upload an image, or paste a URL. Uploaded image will be used as the product thumbnail.</p>
    </div>

    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-bold">Alternative Images</label>
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach($alternativeImages as $index => $alternativeValue)
                @php
                    $alternativePreview = \App\Support\PublicAssetUrl::resolve($alternativeValue);
                @endphp
                <div class="rounded-xl border border-slate-200 p-3">
                    <p class="text-xs font-bold text-slate-600">Alternative image {{ $index }}</p>
                    @if($alternativePreview)
                        <div class="mt-2 h-24 w-24 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                            <img loading="lazy" decoding="async" src="{{ $alternativePreview }}" alt="{{ ($product->name ?: 'Product') . ' alternative image ' . $index }}" class="h-full w-full object-cover">
                        </div>
                    @endif
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <input type="file" name="alternative_image_{{ $index }}_file" accept="image/*" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <input type="text" name="metadata[alternative_image_{{ $index }}]" value="{{ $alternativeValue }}" placeholder="Or paste image URL" class="rounded-xl border-slate-300">
                    </div>
                </div>
            @endforeach
        </div>
        <p class="mt-1 text-xs text-slate-500">Add up to two extra product images. Uploaded files replace pasted URLs for that slot.</p>
    </div>
</div>
<input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" placeholder="Short Description" class="w-full rounded-xl border-slate-300">
<div class="rounded-xl border border-slate-300 bg-white" x-data="richTextEditor()" x-init="init($refs.descriptionInput, $refs.descriptionEditor)">
    <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50 px-3 py-2">
        <button type="button" title="Bold" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-sm font-extrabold hover:bg-slate-100" @click="format('bold')">B</button>
        <button type="button" title="Italic" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-sm italic hover:bg-slate-100" @click="format('italic')">I</button>
        <button type="button" title="Underline" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-sm underline hover:bg-slate-100" @click="format('underline')">U</button>
        <span class="mx-1 h-6 w-px bg-slate-200"></span>
        <button type="button" title="Heading" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-xs font-extrabold hover:bg-slate-100" @click="block('h3')">H</button>
        <button type="button" title="Paragraph" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-xs font-semibold hover:bg-slate-100" @click="block('p')">P</button>
        <button type="button" title="Bulleted list" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-sm hover:bg-slate-100" @click="format('insertUnorderedList')">•</button>
        <button type="button" title="Numbered list" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-sm hover:bg-slate-100" @click="format('insertOrderedList')">1.</button>
        <span class="mx-1 h-6 w-px bg-slate-200"></span>
        <button type="button" title="Add link" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-xs font-bold hover:bg-slate-100" @click="link()">Link</button>
        <button type="button" title="Clear formatting" class="h-8 min-w-8 rounded border border-slate-200 bg-white px-2 text-xs font-bold hover:bg-slate-100" @click="format('removeFormat')">Tx</button>
    </div>
    <div
        x-ref="descriptionEditor"
        contenteditable="true"
        role="textbox"
        aria-label="Description"
        class="rich-text-editor min-h-[220px] w-full overflow-auto rounded-b-xl px-4 py-3 text-sm leading-7 outline-none focus:ring-2 focus:ring-slate-300"
        @input="sync()"
        @blur="sync()"
    ></div>
    <textarea x-ref="descriptionInput" name="description" class="hidden">{{ old('description', $product->description) }}</textarea>
</div>

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
                        <img loading="lazy" decoding="async" src="{{ $media->url }}" alt="{{ $media->alt_text ?: $media->title }}" class="h-full w-full object-cover">
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
