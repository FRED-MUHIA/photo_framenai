<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $artworks = Artwork::query()
            ->with(['user', 'orderItem.order'])
            ->withCount('orderItems')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.artworks.index', compact('artworks', 'status'));
    }

    public function show(Artwork $artwork)
    {
        $artwork->load(['user', 'orderItem.order'])->loadCount('orderItems');

        return view('admin.artworks.show', compact('artwork'));
    }

    public function download(Artwork $artwork)
    {
        abort_unless(Storage::disk('public')->exists($artwork->file_path), 404);

        return Storage::disk('public')->download($artwork->file_path, $artwork->original_name);
    }

    public function updateStatus(Request $request, Artwork $artwork)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processed,rejected'],
            'validation_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $artwork->update([
            'status' => $validated['status'],
            'validation_notes' => $validated['validation_notes'] ?? $artwork->validation_notes,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Artwork status updated.');
    }

    public function destroy(Artwork $artwork)
    {
        if ($this->artworkIsInUse($artwork)) {
            return back()->withErrors(['artwork' => 'This artwork is attached to an order and cannot be deleted.']);
        }

        Storage::disk('public')->delete($artwork->file_path);
        $artwork->delete();

        return redirect()->route('admin.artworks.index')->with('status', 'Unused artwork deleted.');
    }

    private function artworkIsInUse(Artwork $artwork): bool
    {
        return filled($artwork->order_item_id)
            || OrderItem::query()->where('artwork_id', $artwork->id)->exists();
    }
}
