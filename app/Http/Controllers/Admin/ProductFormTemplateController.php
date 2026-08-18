<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFormTemplate;
use App\Models\ProductOptionValue;
use App\Models\ProductQuantityPrice;
use App\Support\ImageOptimizer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductFormTemplateController extends Controller
{
    public function index()
    {
        if (! $this->tablesReady()) {
            $templates = new LengthAwarePaginator([], 0, 15);

            return view('admin.product-form-templates.index', [
                'templates' => $templates,
                'missingMigration' => true,
            ]);
        }

        $templates = ProductFormTemplate::query()
            ->withCount('products')
            ->latest()
            ->paginate(15);

        return view('admin.product-form-templates.index', [
            'templates' => $templates,
            'missingMigration' => false,
        ]);
    }

    public function create()
    {
        abort_unless($this->tablesReady(), 503, 'Run database migrations before creating product forms.');

        return view('admin.product-form-templates.create', [
            'template' => new ProductFormTemplate(),
            'optionGroupsJson' => $this->defaultOptionGroups(),
            'quantityPricesJson' => $this->defaultQuantityPrices(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($this->tablesReady(), 503, 'Run database migrations before creating product forms.');

        $validated = $this->validateTemplate($request);
        $validated = $this->attachUploadedOptionImages($request, $validated);

        ProductFormTemplate::query()->create($this->payload($validated));

        return redirect()->route('admin.product-form-templates.index')->with('status', 'Product form created.');
    }

    public function edit(ProductFormTemplate $productFormTemplate)
    {
        abort_unless($this->tablesReady(), 503, 'Run database migrations before editing product forms.');

        return view('admin.product-form-templates.edit', [
            'template' => $productFormTemplate,
            'optionGroupsJson' => old('option_groups_json', json_encode($productFormTemplate->option_groups ?: [], JSON_PRETTY_PRINT)),
            'quantityPricesJson' => old('quantity_prices_json', json_encode($productFormTemplate->quantity_prices ?: [], JSON_PRETTY_PRINT)),
        ]);
    }

    public function update(Request $request, ProductFormTemplate $productFormTemplate)
    {
        abort_unless($this->tablesReady(), 503, 'Run database migrations before editing product forms.');

        $validated = $this->validateTemplate($request, $productFormTemplate->id);
        $validated = $this->attachUploadedOptionImages($request, $validated);

        $updatedProductCount = 0;

        DB::transaction(function () use ($productFormTemplate, $validated, &$updatedProductCount) {
            $productFormTemplate->update($this->payload($validated, $productFormTemplate->id));
            $productFormTemplate->refresh();
            $updatedProductCount = $this->syncTemplateToProducts($productFormTemplate);
        });

        return redirect()
            ->route('admin.product-form-templates.index')
            ->with('status', 'Product form updated. ' . $updatedProductCount . ' linked product(s) refreshed.');
    }

    public function destroy(ProductFormTemplate $productFormTemplate)
    {
        abort_unless($this->tablesReady(), 503, 'Run database migrations before deleting product forms.');

        $productFormTemplate->delete();

        return back()->with('status', 'Product form deleted.');
    }

    private function validateTemplate(Request $request, ?int $templateId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'unique:product_form_templates,slug,' . $templateId],
            'description' => ['nullable', 'string'],
            'option_groups_json' => ['nullable', 'string'],
            'quantity_prices_json' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'settings.upload_enabled' => ['nullable', 'boolean'],
            'settings.upload_label' => ['nullable', 'string', 'max:190'],
            'settings.upload_help' => ['nullable', 'string', 'max:255'],
            'option_images' => ['nullable', 'array'],
            'option_images.*' => ['nullable', 'array'],
            'option_images.*.*' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function attachUploadedOptionImages(Request $request, array $validated): array
    {
        $groups = $this->decodeJson($validated['option_groups_json'] ?? null, 'option_groups_json');

        foreach ($request->file('option_images', []) as $groupIndex => $valueFiles) {
            if (! is_array($valueFiles)) {
                continue;
            }

            foreach ($valueFiles as $valueIndex => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                if (! isset($groups[$groupIndex]['values'][$valueIndex])) {
                    continue;
                }

                $path = $file->store('product-option-images', 'public');
                ImageOptimizer::optimizeCommonPublicVariants($path);
                $groups[$groupIndex]['values'][$valueIndex]['image_url'] = '/storage/' . $path;
            }
        }

        foreach ($groups as &$group) {
            foreach (($group['values'] ?? []) as &$value) {
                unset($value['preview']);
            }
        }

        unset($value, $group, $validated['option_images']);

        $validated['option_groups_json'] = json_encode($groups);

        return $validated;
    }

    private function tablesReady(): bool
    {
        return Schema::hasTable('product_form_templates') && Schema::hasColumn('products', 'product_form_template_id');
    }

    private function syncTemplateToProducts(ProductFormTemplate $template): int
    {
        $count = 0;

        Product::query()
            ->where('product_form_template_id', $template->id)
            ->with('optionGroups')
            ->chunkById(50, function ($products) use ($template, &$count) {
                foreach ($products as $product) {
                    $this->syncProductOptions($product, $template->option_groups ?: []);
                    $this->syncProductQuantityPricing($product, $template->quantity_prices ?: []);
                    $this->syncProductUploadSettings($product, $template->settings ?: []);
                    $count++;
                }
            });

        return $count;
    }

    private function syncProductOptions(Product $product, array $groups): void
    {
        $groupIds = $product->optionGroups()->pluck('id');

        if ($groupIds->isNotEmpty()) {
            ProductOptionValue::query()
                ->whereIn('product_option_group_id', $groupIds)
                ->delete();
        }

        $product->optionGroups()->delete();

        $usedCodes = [];

        foreach ($groups as $index => $groupPayload) {
            $groupName = data_get($groupPayload, 'name', 'Option Group ' . ($index + 1));
            $codeSeed = data_get($groupPayload, 'code') ?: $groupName ?: 'group-' . ($index + 1);
            $baseCode = Str::of($codeSeed)->slug('_')->toString() ?: 'group_' . ($index + 1);
            $code = $baseCode;
            $counter = 2;

            while (in_array($code, $usedCodes, true)) {
                $code = $baseCode . '_' . $counter;
                $counter++;
            }

            $usedCodes[] = $code;

            $group = $product->optionGroups()->create([
                'name' => $groupName,
                'code' => $code,
                'input_type' => data_get($groupPayload, 'input_type', 'select'),
                'is_required' => (bool) data_get($groupPayload, 'is_required', true),
                'sort_order' => $index,
            ]);

            if (data_get($groupPayload, 'input_type') === 'text') {
                continue;
            }

            foreach ((array) data_get($groupPayload, 'values', []) as $valueIndex => $valuePayload) {
                $valueLabel = data_get($valuePayload, 'label', 'Option ' . ($valueIndex + 1));
                $value = data_get($valuePayload, 'value') ?: Str::slug($valueLabel) ?: 'option-' . ($valueIndex + 1);

                $group->values()->create([
                    'label' => $valueLabel,
                    'value' => $value,
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

    private function syncProductQuantityPricing(Product $product, array $rows): void
    {
        ProductQuantityPrice::query()->where('product_id', $product->id)->delete();

        foreach ($rows as $row) {
            $quantity = (int) data_get($row, 'quantity', 0);

            if ($quantity < 1) {
                continue;
            }

            ProductQuantityPrice::query()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => (float) data_get($row, 'price', 0),
                'discount_percentage' => (float) data_get($row, 'discount_percentage', 0),
                'delivery_days' => data_get($row, 'delivery_days') !== null ? (int) data_get($row, 'delivery_days') : null,
                'is_active' => (bool) data_get($row, 'is_active', true),
            ]);
        }
    }

    private function syncProductUploadSettings(Product $product, array $settings): void
    {
        $metadata = $product->metadata ?: [];
        $metadata['upload_enabled'] = (bool) data_get($settings, 'upload_enabled', true);
        $metadata['upload_label'] = data_get($settings, 'upload_label', 'Upload Design (Optional)');
        $metadata['upload_help'] = data_get($settings, 'upload_help', 'PDF, AI, PSD, JPG, PNG (max 50MB)');

        $product->update(['metadata' => $metadata]);
    }

    private function payload(array $validated, ?int $ignoreId = null): array
    {
        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        return [
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($slug, $ignoreId),
            'description' => $validated['description'] ?? null,
            'option_groups' => $this->decodeJson($validated['option_groups_json'] ?? null, 'option_groups_json'),
            'quantity_prices' => $this->decodeJson($validated['quantity_prices_json'] ?? null, 'quantity_prices_json'),
            'settings' => [
                'upload_enabled' => (bool) data_get($validated, 'settings.upload_enabled', true),
                'upload_label' => data_get($validated, 'settings.upload_label', 'Upload Design (Optional)'),
                'upload_help' => data_get($validated, 'settings.upload_help', 'PDF, AI, PSD, JPG, PNG (max 50MB)'),
            ],
            'is_active' => ! empty($validated['is_active']),
        ];
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = $slug ?: Str::random(8);
        $candidate = $baseSlug;
        $counter = 2;

        while (ProductFormTemplate::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $candidate;
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

    private function defaultOptionGroups(): string
    {
        return json_encode([
            [
                'name' => 'Print Option',
                'code' => 'print_option',
                'input_type' => 'buttons',
                'is_required' => true,
                'values' => [
                    [
                        'label' => 'A4 Size',
                        'value' => 'a4',
                        'price_modifier' => 0,
                        'delivery_modifier' => 0,
                        'is_default' => true,
                        'image_url' => '',
                    ],
                    [
                        'label' => 'A3 Size',
                        'value' => 'a3',
                        'price_modifier' => 1700,
                        'delivery_modifier' => 0,
                        'is_default' => false,
                        'image_url' => '',
                    ],
                ],
            ],
        ], JSON_PRETTY_PRINT);
    }

    private function defaultQuantityPrices(): string
    {
        return json_encode([
            ['quantity' => 100, 'price' => 3500, 'discount_percentage' => 0, 'delivery_days' => 4, 'is_active' => true],
            ['quantity' => 250, 'price' => 7800, 'discount_percentage' => 0, 'delivery_days' => 4, 'is_active' => true],
        ], JSON_PRETTY_PRINT);
    }
}
