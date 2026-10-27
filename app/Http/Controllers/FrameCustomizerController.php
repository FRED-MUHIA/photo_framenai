<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FrameCustomizerController extends Controller
{
    public function index()
    {
        return $this->renderCustomizer();
    }

    public function edit(Request $request, string $lineKey)
    {
        $items = app(CartService::class)->all();
        $index = collect($items)->search(fn ($item) => ($item['line_key'] ?? null) === $lineKey && ! empty($item['frame_customization']));
        abort_if($index === false, 404);
        $customization = $items[$index]['frame_customization'];
        abort_unless(Storage::disk('local')->exists($customization['original_image_path'] ?? ''), 404);

        return $this->renderCustomizer($customization, $lineKey);
    }

    public function existingPhoto(Request $request, string $lineKey)
    {
        $item = collect(app(CartService::class)->all())->first(fn ($item) => ($item['line_key'] ?? null) === $lineKey && ! empty($item['frame_customization']));
        abort_unless($item, 404);
        $customization = $item['frame_customization'];
        $path = $customization['original_image_path'] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $customization['original_image_mime'] ?? 'application/octet-stream',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function accessoryOptions(Frame $frame): array
    {
        $options = collect($frame->accessories ?? [])->values();
        $existingNames = $options
            ->map(fn ($option) => mb_strtolower(trim((string) ($option['name'] ?? ''))))
            ->all();
        $defaults = [
            ['id' => 'with-glass', 'name' => 'With glass', 'price' => 0],
            ['id' => 'stand-handle', 'name' => 'Stand handle', 'price' => 0],
            ['id' => 'wall-mount', 'name' => 'Wall mount', 'price' => 0],
        ];

        foreach ($defaults as $option) {
            if (! in_array(mb_strtolower($option['name']), $existingNames, true)) {
                $options->push($option);
            }
        }

        return $options->values()->all();
    }

    private function renderCustomizer(?array $initialCustomization = null, ?string $lineKey = null)
    {
        // Keep active, sold-out designs in the gallery so guests can still preview them.
        // The add-to-cart action validates stock separately and will block unavailable frames.
        $frames = Frame::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $catalogue = $frames->map(fn (Frame $frame) => [
            'id' => $frame->id,
            'name' => $frame->name,
            'sku' => $frame->sku,
            'description' => $frame->description,
            'material' => $frame->material,
            'asset_type' => $frame->asset_type,
            'thumbnail' => $frame->thumbnail_path ? asset('storage/' . $frame->thumbnail_path) : null,
            'overlay' => $frame->overlay_path ? asset('storage/' . $frame->overlay_path) : null,
            'landscape_overlay' => $frame->landscape_overlay_path ? asset('storage/' . $frame->landscape_overlay_path) : null,
            'texture' => $frame->texture_path ? asset('storage/' . $frame->texture_path) : null,
            'base_price' => (float) $frame->base_price,
            'min_width_cm' => (float) $frame->min_width_cm,
            'max_width_cm' => (float) $frame->max_width_cm,
            'width_price_per_cm' => (float) $frame->width_price_per_cm,
            'orientations' => $frame->orientations ?? [],
            'sizes' => $frame->sizes ?? [],
            'mats' => $frame->mats ?? [],
            'accessories' => $this->accessoryOptions($frame),
            'inventory' => $frame->inventory,
        ])->values();

        return view('frames.customize', compact('frames', 'catalogue', 'initialCustomization', 'lineKey'));
    }

    public function addToCart(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'frame_id' => ['required', 'integer', 'exists:frames,id'],
            'orientation' => ['required', 'in:portrait,landscape'],
            'frame_width_cm' => ['required', 'numeric', 'min:0.5', 'max:30'],
            'image_rotation' => ['required', 'numeric', 'between:-360,360'],
            'photo_tone' => ['nullable', 'numeric', 'between:-100,100'],
            'frame_rotation' => ['required', 'numeric', 'between:-360,360'],
            'zoom' => ['required', 'numeric', 'between:0.5,4'],
            'pan_x' => ['required', 'numeric', 'between:-100,100'],
            'pan_y' => ['required', 'numeric', 'between:-100,100'],
            'mat_color' => ['nullable', 'string', 'max:30'],
            'size_name' => ['required', 'string', 'max:80'],
            'unit' => ['required', 'in:cm,in'],
            'width_cm' => ['required', 'numeric', 'gt:0', 'max:300'],
            'height_cm' => ['required', 'numeric', 'gt:0', 'max:300'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'accessories' => ['nullable', 'array', 'max:20'],
            'accessories.*' => ['string', 'max:80'],
            'existing_line_key' => ['nullable', 'string', 'max:80'],
            'save_action' => ['nullable', 'in:cart,another'],
            'photo' => ['required_without:existing_line_key', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'preview' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:10240'],
        ]);

        $cartItems = $cart->all();
        $existingIndex = false;
        $existingItem = null;
        if (! empty($validated['existing_line_key'])) {
            $existingIndex = collect($cartItems)->search(fn ($item) => ($item['line_key'] ?? null) === $validated['existing_line_key'] && ! empty($item['frame_customization']));
            if ($existingIndex === false) {
                throw ValidationException::withMessages(['existing_line_key' => 'This saved frame is no longer in your cart.']);
            }
            $existingItem = $cartItems[$existingIndex];
        }

        $frame = Frame::query()->where('is_active', true)->findOrFail($validated['frame_id']);
        $orientation = $validated['orientation'];
        if (! in_array($orientation, $frame->orientations ?? [], true)) {
            throw ValidationException::withMessages(['orientation' => 'This frame is not available in the selected orientation.']);
        }
        $selectedMat = null;
        if (! empty($validated['mat_color'])) {
            $selectedMat = collect($frame->mats ?? [])->firstWhere('color', $validated['mat_color']);
            if (! $selectedMat) {
                throw ValidationException::withMessages(['mat_color' => 'This mat option is not available for the selected frame.']);
            }
        }
        $width = (float) $validated['frame_width_cm'];
        if ($width < (float) $frame->min_width_cm || $width > (float) $frame->max_width_cm) {
            throw ValidationException::withMessages(['frame_width_cm' => 'Frame profile width is outside this design’s range.']);
        }
        if ($frame->inventory !== null && $frame->inventory < (int) $validated['quantity']) {
            throw ValidationException::withMessages(['quantity' => 'There is not enough stock for this frame.']);
        }

        $selectedSize = collect($frame->sizes ?? [])->first(fn ($size) => ($size['name'] ?? null) === $validated['size_name']);
        if (count($frame->sizes ?? []) && ! $selectedSize) {
            throw ValidationException::withMessages(['size_name' => 'Choose a print size available for this frame.']);
        }
        if ($selectedSize) {
            $printWidth = (float) $selectedSize['width_cm'];
            $printHeight = (float) $selectedSize['height_cm'];
            if (($orientation === 'landscape' && $printWidth < $printHeight) || ($orientation === 'portrait' && $printWidth > $printHeight)) {
                [$printWidth, $printHeight] = [$printHeight, $printWidth];
            }
        } else {
            $printWidth = (float) $validated['width_cm'];
            $printHeight = (float) $validated['height_cm'];
        }
        if (($orientation === 'portrait' && $printWidth > $printHeight) || ($orientation === 'landscape' && $printWidth < $printHeight)) {
            throw ValidationException::withMessages(['orientation' => 'Print dimensions must match the selected production orientation.']);
        }

        $availableAccessories = collect($this->accessoryOptions($frame))->keyBy(fn ($item) => (string) ($item['id'] ?? $item['name'] ?? ''));
        $accessoryKeys = array_values(array_unique($validated['accessories'] ?? []));
        foreach ($accessoryKeys as $key) {
            if (! $availableAccessories->has($key)) {
                throw ValidationException::withMessages(['accessories' => 'One of the selected extras is unavailable.']);
            }
        }
        $accessorySnapshot = collect($accessoryKeys)->map(fn ($key) => $availableAccessories->get($key))->values()->all();
        $widthAdjustment = max(0, $width - (float) $frame->min_width_cm) * (float) $frame->width_price_per_cm;
        $sizeAdjustment = (float) ($selectedSize['price'] ?? 0);
        $matAdjustment = (float) ($selectedMat['price'] ?? 0);
        $accessoryAdjustment = collect($accessorySnapshot)->sum(fn ($item) => (float) ($item['price'] ?? 0));
        $unitPrice = round((float) $frame->base_price + $widthAdjustment + $sizeAdjustment + $matAdjustment + $accessoryAdjustment, 2);
        $quantity = (int) $validated['quantity'];
        $photo = $request->file('photo');
        if ($photo) {
            $photoSize = @getimagesize($photo->getRealPath());
            if (! $photoSize || $photoSize[0] > 12000 || $photoSize[1] > 12000 || ($photoSize[0] * $photoSize[1]) > 60000000) {
                throw ValidationException::withMessages(['photo' => 'Image dimensions are too large. Upload an image under 60 megapixels.']);
            }
            $photoPath = $photo->store('private/frame-orders/' . now()->format('Y/m'), 'local');
            $photoName = Str::limit($photo->getClientOriginalName(), 180, '');
            $photoMime = $photo->getMimeType();
            $photoBytes = $photo->getSize();
        } elseif ($existingItem) {
            $old = $existingItem['frame_customization'];
            $photoPath = $old['original_image_path'];
            $photoName = $old['original_image_name'];
            $photoMime = $old['original_image_mime'];
            $photoBytes = $old['original_image_size'];
            $photoSize = [$old['original_image_width_px'], $old['original_image_height_px']];
        } else {
            throw ValidationException::withMessages(['photo' => 'Choose a photograph before adding this frame to your cart.']);
        }
        $previewPath = $request->hasFile('preview')
            ? $request->file('preview')->store('private/frame-previews/' . now()->format('Y/m'), 'local')
            : ($existingItem['frame_customization']['preview_path'] ?? null);
        $customization = [
            'frame_id' => $frame->id,
            'frame_name' => $frame->name,
            'frame_sku' => $frame->sku,
            'frame_material' => $frame->material,
            'asset_type' => $frame->asset_type,
            'frame_overlay_path' => $orientation === 'landscape' ? ($frame->landscape_overlay_path ?: $frame->overlay_path) : $frame->overlay_path,
            'frame_texture_path' => $frame->texture_path,
            'orientation' => $orientation,
            'size_name' => $validated['size_name'],
            'unit' => $validated['unit'],
            'width_cm' => $printWidth,
            'height_cm' => $printHeight,
            'frame_width_cm' => $width,
            'frame_rotation' => (float) $validated['frame_rotation'],
            'image_rotation' => (float) $validated['image_rotation'],
            'photo_tone' => (float) ($validated['photo_tone'] ?? 0),
            'zoom' => (float) $validated['zoom'],
            'pan_x' => (float) $validated['pan_x'],
            'pan_y' => (float) $validated['pan_y'],
            'mat_color' => $validated['mat_color'] ?? null,
            'mat' => $selectedMat,
            'accessories' => $accessorySnapshot,
            'original_image_path' => $photoPath,
            'original_image_name' => $photoName,
            'original_image_mime' => $photoMime,
            'original_image_size' => $photoBytes,
            'original_image_width_px' => $photoSize[0],
            'original_image_height_px' => $photoSize[1],
            'preview_path' => $previewPath,
            'price_breakdown' => [
                'base_frame' => (float) $frame->base_price,
                'width_adjustment' => round($widthAdjustment, 2),
                'size_adjustment' => $sizeAdjustment,
                'mat' => round($matAdjustment, 2),
                'accessories' => round($accessoryAdjustment, 2),
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'total' => round($unitPrice * $quantity, 2),
                'currency' => config('shop.currency', 'KES'),
            ],
        ];

        $cartItem = [
            'line_key' => $existingItem['line_key'] ?? (string) Str::uuid(),
            'product_id' => null,
            'product_name' => 'Custom picture frame — ' . $frame->name,
            'sku' => $frame->sku,
            'thumbnail' => $frame->thumbnail_path ? asset('storage/' . $frame->thumbnail_path) : null,
            'quantity' => $quantity,
            'min_quantity' => 1,
            'selected_options' => [],
            'selected_values' => [],
            'custom_fields' => [],
            'unit_price' => $unitPrice,
            'line_total' => round($unitPrice * $quantity, 2),
            'delivery_days' => 7,
            'delivery_date' => today()->addDays(7)->toDateString(),
            'rush_order' => false,
            'rush_fee' => 0,
            'shipping_option' => 'standard',
            'artwork_id' => null,
            'artwork_name' => $customization['original_image_name'],
            'frame_customization' => $customization,
        ];
        if ($existingIndex !== false) {
            $cart->update($existingIndex, $cartItem);
        } else {
            $cart->add($cartItem);
        }

        $savedMessage = $existingItem ? 'Your custom frame was updated.' : 'Your custom frame was saved to your order.';

        if (($validated['save_action'] ?? 'cart') === 'another') {
            return redirect()->route('frames.customize')->with('status', $savedMessage . ' You can now customize another artwork.');
        }

        return redirect()->route('cart.index')->with('status', $savedMessage);
    }
}
