<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Http\Request;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $artworks = Artwork::query()
            ->with(['user', 'orderItem.order'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.artworks.index', compact('artworks', 'status'));
    }

    public function show(Artwork $artwork)
    {
        $artwork->load(['user', 'orderItem.order']);

        return view('admin.artworks.show', compact('artwork'));
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
}
