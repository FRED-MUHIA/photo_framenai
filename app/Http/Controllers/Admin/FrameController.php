<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frame;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FrameController extends Controller
{
    public function index()
    {
        $frames = Frame::query()->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.frames.index', compact('frames'));
    }

    public function create()
    {
        return view('admin.frames.form', ['frame' => new Frame()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->validatePublishable($request, $data);
        $frame = new Frame($this->attributes($data));
        $this->saveAssets($request, $frame);
        $frame->save();

        return redirect()->route('admin.frames.index')->with('status', 'Frame created.');
    }

    public function edit(Frame $frame)
    {
        return view('admin.frames.form', compact('frame'));
    }

    public function update(Request $request, Frame $frame)
    {
        $data = $this->validated($request, $frame);
        $this->validatePublishable($request, $data, $frame);
        $frame->fill($this->attributes($data));
        $this->saveAssets($request, $frame);
        $frame->save();

        return redirect()->route('admin.frames.index')->with('status', 'Frame updated.');
    }

    public function destroy(Frame $frame)
    {
        if (OrderItem::query()->where('configuration->frame_customization->frame_id', $frame->id)->exists()) {
            return back()->withErrors(['frame' => 'This frame is part of order history. Deactivate it instead of deleting it.']);
        }

        foreach ([$frame->thumbnail_path, $frame->overlay_path, $frame->landscape_overlay_path, $frame->texture_path] as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }

        $frame->delete();

        return back()->with('status', 'Frame deleted.');
    }

    private function validated(Request $request, ?Frame $frame = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'unique:frames,slug,' . ($frame?->id ?? 'NULL')],
            'sku' => ['nullable', 'string', 'max:90', 'unique:frames,sku,' . ($frame?->id ?? 'NULL')],
            'description' => ['nullable', 'string', 'max:5000'],
            'material' => ['nullable', 'string', 'max:120'],
            'asset_type' => ['required', 'in:overlay,texture'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'overlay' => ['nullable', 'image', 'mimes:png,webp', 'max:10240'],
            'landscape_overlay' => ['nullable', 'image', 'mimes:png,webp', 'max:10240'],
            'texture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'min_width_cm' => ['required', 'numeric', 'min:0.5', 'max:30'],
            'max_width_cm' => ['required', 'numeric', 'min:0.5', 'max:30', 'gte:min_width_cm'],
            'width_price_per_cm' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'orientations' => ['required', 'array', 'min:1'],
            'orientations.*' => ['in:portrait,landscape'],
            'sizes_text' => ['nullable', 'string', 'max:10000'],
            'mats_json' => ['nullable', 'string', 'max:10000'],
            'accessories_json' => ['nullable', 'string', 'max:10000'],
            'inventory' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);

        foreach (preg_split('/\R/', (string) ($data['sizes_text'] ?? '')) as $line) {
            if (trim($line) === '') continue;
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 3 || $parts[0] === '' || ! is_numeric($parts[1]) || ! is_numeric($parts[2]) || (float) $parts[1] <= 0 || (float) $parts[2] <= 0 || (float) $parts[1] > 300 || (float) $parts[2] > 300 || (isset($parts[3]) && (! is_numeric($parts[3]) || (float) $parts[3] < 0 || (float) $parts[3] > 10000000))) {
                throw ValidationException::withMessages(['sizes_text' => 'Each print size needs a name, positive width and height in centimetres, and an optional non-negative price adjustment.']);
            }
        }
        $mats = json_decode((string) ($data['mats_json'] ?? '[]'), true);
        if (! is_array($mats) || json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages(['mats_json' => 'Mat options must be a valid JSON array.']);
        }
        foreach ($mats as $mat) {
            if (! is_array($mat) || trim((string) ($mat['name'] ?? '')) === '' || ! preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($mat['color'] ?? '')) || (isset($mat['price']) && (! is_numeric($mat['price']) || (float) $mat['price'] < 0 || (float) $mat['price'] > 10000000))) {
                throw ValidationException::withMessages(['mats_json' => 'Each mat needs a name, a six-digit hex colour, and an optional non-negative price.']);
            }
        }
        $accessories = json_decode((string) ($data['accessories_json'] ?? '[]'), true);
        if (! is_array($accessories) || json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages(['accessories_json' => 'Accessories must be a valid JSON array.']);
        }
        foreach ($accessories as $accessory) {
            if (! is_array($accessory) || trim((string) ($accessory['name'] ?? '')) === '' || (isset($accessory['price']) && (! is_numeric($accessory['price']) || (float) $accessory['price'] < 0))) {
                throw ValidationException::withMessages(['accessories_json' => 'Each accessory needs a name and a non-negative optional price.']);
            }
        }

        return $data;
    }

    private function validatePublishable(Request $request, array $data, ?Frame $frame = null): void
    {
        if (! ($data['is_active'] ?? false)) return;
        if ($data['asset_type'] === 'overlay' && ! $request->hasFile('overlay') && ! $frame?->overlay_path) {
            throw ValidationException::withMessages(['overlay' => 'Upload a transparent frame overlay before publishing this design.']);
        }
        if ($data['asset_type'] === 'texture' && ! $request->hasFile('texture') && ! $frame?->texture_path) {
            throw ValidationException::withMessages(['texture' => 'Upload a frame texture before publishing this design.']);
        }
    }

    private function attributes(array $data): array
    {
        $sizes = collect(preg_split('/\R/', (string) ($data['sizes_text'] ?? '')))
            ->map(function ($line) {
                $parts = array_map('trim', explode('|', $line));
                if (count($parts) < 3 || $parts[0] === '') return null;
                return ['name' => $parts[0], 'width_cm' => (float) $parts[1], 'height_cm' => (float) $parts[2], 'price' => (float) ($parts[3] ?? 0)];
            })->filter()->values()->all();
        $accessories = json_decode((string) ($data['accessories_json'] ?? '[]'), true);
        if (! is_array($accessories)) $accessories = [];
        $mats = json_decode((string) ($data['mats_json'] ?? '[]'), true);
        if (! is_array($mats)) $mats = [];

        return [
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'sku' => $data['sku'] ?: null,
            'description' => $data['description'] ?? null,
            'material' => $data['material'] ?? null,
            'asset_type' => $data['asset_type'],
            'base_price' => $data['base_price'],
            'min_width_cm' => $data['min_width_cm'],
            'max_width_cm' => $data['max_width_cm'],
            'width_price_per_cm' => $data['width_price_per_cm'] ?? 0,
            'orientations' => array_values($data['orientations']),
            'sizes' => $sizes,
            'mats' => $mats,
            'accessories' => $accessories,
            'inventory' => $data['inventory'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    private function saveAssets(Request $request, Frame $frame): void
    {
        foreach (['thumbnail' => 'thumbnail_path', 'overlay' => 'overlay_path', 'landscape_overlay' => 'landscape_overlay_path', 'texture' => 'texture_path'] as $input => $attribute) {
            if ($request->hasFile($input)) {
                if ($frame->{$attribute} && ! $this->assetIsInOrderHistory($frame->{$attribute})) {
                    Storage::disk('public')->delete($frame->{$attribute});
                }
                $frame->{$attribute} = $request->file($input)->store('frames/' . now()->format('Y/m'), 'public');
            }
        }
    }

    private function assetIsInOrderHistory(string $path): bool
    {
        return OrderItem::query()->where(function ($query) use ($path) {
            $query->where('configuration->frame_customization->frame_overlay_path', $path)
                ->orWhere('configuration->frame_customization->frame_texture_path', $path);
        })->exists();
    }
}
