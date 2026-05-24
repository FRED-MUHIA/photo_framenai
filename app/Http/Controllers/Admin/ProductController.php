<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MediaItem;
use App\Models\Product;
use App\Models\ProductFormTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->with(['category', 'categories'])->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();
        $mediaItems = MediaItem::query()->latest()->take(120)->get();
        $formTemplates = ProductFormTemplate::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', [
            'categories' => $categories,
            'mediaItems' => $mediaItems,
            'formTemplates' => $formTemplates,
            'product' => new Product(),
            'optionGroupsJson' => "",
            'quantityPricesJson' => "",
            'selectedCategoryIds' => [],
            'selectedMediaIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated = $this->attachUploadedThumbnail($request, $validated);

        DB::transaction(function () use ($validated) {
            $validated = $this->applyFormTemplatePayload($validated);
            $categoryIds = $this->resolveCategoryIds($validated);
            $product = Product::query()->create([
                ...$this->mapProductPayload($validated),
                'category_id' => $categoryIds[0],
                'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            ]);

            $product->categories()->sync($categoryIds);
            $this->syncMedia($product, $validated['media_ids'] ?? []);
            $this->syncOptions($product, $validated['option_groups_json'] ?? null);
            $this->syncQuantityPricing($product, $validated['quantity_prices_json'] ?? null);
        });

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $product->load(['optionGroups.values', 'quantityPrices']);

        $categories = Category::query()->orderBy('name')->get();
        $mediaItems = MediaItem::query()->latest()->take(120)->get();
        $formTemplates = ProductFormTemplate::query()->where('is_active', true)->orderBy('name')->get();
        $optionGroupsJson = $product->optionGroups->map(fn ($group) => [
            'name' => $group->name,
            'code' => $group->code,
            'input_type' => $group->input_type,
            'is_required' => $group->is_required,
            'values' => $group->values->map(fn ($value) => [
                'label' => $value->label,
                'value' => $value->value,
                'price_modifier' => (float) $value->price_modifier,
                'delivery_modifier' => (int) $value->delivery_modifier,
                'is_default' => (bool) $value->is_default,
                'image_url' => data_get($value->metadata, 'image_url', ''),
            ])->values()->all(),
        ])->values()->toJson(JSON_PRETTY_PRINT);

        $quantityPricesJson = $product->quantityPrices->map(fn ($row) => [
            'quantity' => $row->quantity,
            'price' => (float) $row->price,
            'discount_percentage' => (float) $row->discount_percentage,
            'delivery_days' => $row->delivery_days,
            'is_active' => (bool) $row->is_active,
        ])->values()->toJson(JSON_PRETTY_PRINT);

        $selectedCategoryIds = $product->categories()->pluck('categories.id')->all();
        if (empty($selectedCategoryIds) && $product->category_id) {
            $selectedCategoryIds = [$product->category_id];
        }

        $selectedMediaIds = $product->mediaItems()->pluck('media_items.id')->all();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'mediaItems',
            'formTemplates',
            'optionGroupsJson',
            'quantityPricesJson',
            'selectedCategoryIds',
            'selectedMediaIds'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);
        $validated = $this->attachUploadedThumbnail($request, $validated);

        DB::transaction(function () use ($product, $validated) {
            $validated = $this->applyFormTemplatePayload($validated);
            $categoryIds = $this->resolveCategoryIds($validated);
            $product->update([
                ...$this->mapProductPayload($validated),
                'category_id' => $categoryIds[0],
                'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            ]);

            $product->categories()->sync($categoryIds);
            $this->syncMedia($product, $validated['media_ids'] ?? []);
            $this->syncOptions($product, $validated['option_groups_json'] ?? null);
            $this->syncQuantityPricing($product, $validated['quantity_prices_json'] ?? null);
        });

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'category_ids' => ['required_without:category_id', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
            'product_form_template_id' => ['nullable', 'exists:product_form_templates,id'],
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'unique:products,slug,' . $productId],
            'sku' => ['nullable', 'string', 'max:90', 'unique:products,sku,' . $productId],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'min_quantity' => ['required', 'integer', 'min:1'],
            'base_delivery_days' => ['required', 'integer', 'min:1'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'metadata' => ['nullable', 'array'],
            'metadata.meta_title' => ['nullable', 'string', 'max:190'],
            'metadata.meta_description' => ['nullable', 'string', 'max:255'],
            'metadata.seo_keywords' => ['nullable', 'string', 'max:255'],
            'metadata.configurator_intro' => ['nullable', 'string', 'max:255'],
            'metadata.upload_enabled' => ['nullable', 'boolean'],
            'metadata.upload_label' => ['nullable', 'string', 'max:190'],
            'metadata.upload_help' => ['nullable', 'string', 'max:255'],
            'media_ids' => ['nullable', 'array'],
            'media_ids.*' => ['exists:media_items,id'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'option_groups_json' => ['nullable', 'string'],
            'quantity_prices_json' => ['nullable', 'string'],
        ]);
    }

    private function mapProductPayload(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'],
            'min_quantity' => $validated['min_quantity'],
            'base_delivery_days' => $validated['base_delivery_days'],
            'thumbnail' => $validated['thumbnail'] ?? null,
            'metadata' => [
                'meta_title' => data_get($validated, 'metadata.meta_title'),
                'meta_description' => data_get($validated, 'metadata.meta_description'),
                'seo_keywords' => data_get($validated, 'metadata.seo_keywords'),
                'configurator_intro' => data_get($validated, 'metadata.configurator_intro'),
                'upload_enabled' => (bool) data_get($validated, 'metadata.upload_enabled', true),
                'upload_label' => data_get($validated, 'metadata.upload_label'),
                'upload_help' => data_get($validated, 'metadata.upload_help'),
            ],
            'product_form_template_id' => $validated['product_form_template_id'] ?? null,
            'is_featured' => ! empty($validated['is_featured']),
            'is_active' => ! empty($validated['is_active']),
        ];
    }

    private function attachUploadedThumbnail(Request $request, array $validated): array
    {
        if (! $request->hasFile('thumbnail_file')) {
            return $validated;
        }

        $path = $request->file('thumbnail_file')->store('product-thumbnails', 'public');
        $validated['thumbnail'] = '/storage/' . $path;

        return $validated;
    }

    private function resolveCategoryIds(array $validated): array
    {
        $categoryIds = array_values(array_filter(array_map(
            static fn ($id) => (int) $id,
            (array) ($validated['category_ids'] ?? [])
        )));

        if (empty($categoryIds) && ! empty($validated['category_id'])) {
            $categoryIds = [(int) $validated['category_id']];
        }

        if (empty($categoryIds)) {
            throw ValidationException::withMessages(['category_ids' => 'Please select at least one category.']);
        }

        return array_values(array_unique($categoryIds));
    }

    private function decodeJson(?string $raw, string $field): array
    {
        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw ValidationException::withMessages([$field => 'Please provide valid JSON array data.']);
        }

        return $decoded;
    }

    private function syncOptions(Product $product, ?string $rawOptions): void
    {
        $groups = $this->decodeJson($rawOptions, 'option_groups_json');

        $product->optionGroups()->delete();

        foreach ($groups as $index => $groupPayload) {
            $group = $product->optionGroups()->create([
                'name' => data_get($groupPayload, 'name', 'Option Group ' . ($index + 1)),
                'code' => Str::of(data_get($groupPayload, 'code', 'group-' . ($index + 1)))->slug('_')->toString(),
                'input_type' => data_get($groupPayload, 'input_type', 'select'),
                'is_required' => (bool) data_get($groupPayload, 'is_required', true),
                'sort_order' => $index,
            ]);

            foreach ((array) data_get($groupPayload, 'values', []) as $valueIndex => $valuePayload) {
                $group->values()->create([
                    'label' => data_get($valuePayload, 'label', 'Option ' . ($valueIndex + 1)),
                    'value' => data_get($valuePayload, 'value', Str::slug(data_get($valuePayload, 'label', 'option-' . ($valueIndex + 1)))),
                    'price_modifier' => (float) data_get($valuePayload, 'price_modifier', 0),
                    'delivery_modifier' => (int) data_get($valuePayload, 'delivery_modifier', 0),
                    'metadata' => [
                        'image_url' => data_get($valuePayload, 'image_url', data_get($valuePayload, 'metadata.image_url', '')),
                    ],
                    'is_default' => (bool) data_get($valuePayload, 'is_default', false),
                    'sort_order' => $valueIndex,
                ]);
            }
        }
    }

    private function syncQuantityPricing(Product $product, ?string $rawPricing): void
    {
        $rows = $this->decodeJson($rawPricing, 'quantity_prices_json');

        $product->quantityPrices()->delete();

        foreach ($rows as $row) {
            $quantity = (int) data_get($row, 'quantity', 0);

            if ($quantity < 1) {
                continue;
            }

            $product->quantityPrices()->create([
                'quantity' => $quantity,
                'price' => (float) data_get($row, 'price', 0),
                'discount_percentage' => (float) data_get($row, 'discount_percentage', 0),
                'delivery_days' => data_get($row, 'delivery_days') !== null ? (int) data_get($row, 'delivery_days') : null,
                'is_active' => (bool) data_get($row, 'is_active', true),
            ]);
        }
    }

    private function syncMedia(Product $product, array $mediaIds): void
    {
        $payload = [];

        foreach (array_values($mediaIds) as $index => $mediaId) {
            $payload[(int) $mediaId] = ['sort_order' => $index];
        }

        $product->mediaItems()->sync($payload);
    }

    private function applyFormTemplatePayload(array $validated): array
    {
        if (empty($validated['product_form_template_id'])) {
            return $validated;
        }

        $template = ProductFormTemplate::query()->find($validated['product_form_template_id']);

        if (! $template) {
            return $validated;
        }

        $validated['option_groups_json'] = json_encode($template->option_groups ?: []);
        $validated['quantity_prices_json'] = json_encode($template->quantity_prices ?: []);
        $validated['metadata']['upload_enabled'] = data_get($template->settings, 'upload_enabled', true);
        $validated['metadata']['upload_label'] = data_get($template->settings, 'upload_label', 'Upload Design (Optional)');
        $validated['metadata']['upload_help'] = data_get($template->settings, 'upload_help', 'PDF, AI, PSD, JPG, PNG (max 50MB)');

        return $validated;
    }
}
