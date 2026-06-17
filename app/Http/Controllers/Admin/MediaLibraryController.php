<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    public function index()
    {
        $mediaItems = MediaItem::query()->latest()->paginate(24);

        return view('admin.media-library.index', compact('mediaItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => ['nullable', 'array'],
            'files.*' => ['image', 'max:8192'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'title' => ['nullable', 'string', 'max:190'],
            'alt_text' => ['nullable', 'string', 'max:190'],
        ]);

        $created = 0;

        foreach ((array) $request->file('files', []) as $file) {
            $path = $file->store('media-library', 'public');
            ImageOptimizer::optimizePublicPath($path);

            MediaItem::query()->create([
                'type' => 'upload',
                'title' => $validated['title'] ?? $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'alt_text' => $validated['alt_text'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $created++;
        }

        if (! empty($validated['link_url'])) {
            MediaItem::query()->create([
                'type' => 'link',
                'title' => $validated['title'] ?? 'External media link',
                'external_url' => $validated['link_url'],
                'alt_text' => $validated['alt_text'] ?? null,
                'created_by' => auth()->id(),
            ]);
            $created++;
        }

        return back()->with('status', $created > 0 ? "Added {$created} media item(s)." : 'No media uploaded.');
    }

    public function destroy(MediaItem $mediaLibrary)
    {
        if ($mediaLibrary->type === 'upload' && $mediaLibrary->file_path) {
            Storage::disk('public')->delete($mediaLibrary->file_path);
        }

        $mediaLibrary->delete();

        return back()->with('status', 'Media item removed.');
    }
}
