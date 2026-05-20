<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtworkRequest;
use App\Jobs\ProcessArtworkUpload;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtworkController extends Controller
{
    public function create(Request $request)
    {
        $artworks = Artwork::query()
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
            ->when(! auth()->check(), fn ($query) => $query->where('guest_token', $request->session()->getId()))
            ->latest()
            ->take(12)
            ->get();

        return view('artwork.create', compact('artworks'));
    }

    public function store(StoreArtworkRequest $request)
    {
        $file = $request->file('artwork');
        $storedPath = $file->store('artworks/' . now()->format('Y/m'), 'public');

        $artwork = Artwork::query()->create([
            'user_id' => auth()->id(),
            'guest_token' => auth()->check() ? null : $request->session()->getId(),
            'order_item_id' => $request->integer('order_item_id') ?: null,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $storedPath,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'extension' => Str::lower($file->getClientOriginalExtension()),
            'size_bytes' => $file->getSize(),
            'status' => 'pending',
        ]);

        ProcessArtworkUpload::dispatch($artwork->id);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Artwork uploaded successfully.',
                'artwork' => [
                    'id' => $artwork->id,
                    'name' => $artwork->original_name,
                    'status' => $artwork->status,
                    'url' => Storage::url($artwork->file_path),
                ],
            ]);
        }

        return back()->with('status', 'Artwork uploaded successfully. It will be reviewed shortly.');
    }

    public function destroy(Artwork $artwork)
    {
        if (auth()->check()) {
            abort_unless($artwork->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        } else {
            abort_unless($artwork->guest_token === session()->getId(), 403);
        }

        Storage::disk('public')->delete($artwork->file_path);
        $artwork->delete();

        return back()->with('status', 'Artwork removed.');
    }
}
