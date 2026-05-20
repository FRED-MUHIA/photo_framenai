<?php

namespace App\Jobs;

use App\Models\Artwork;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessArtworkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $artworkId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $artwork = Artwork::query()->find($this->artworkId);

        if (! $artwork) {
            return;
        }

        $absolutePath = Storage::disk('public')->path($artwork->file_path);

        if (! file_exists($absolutePath)) {
            $artwork->update([
                'status' => 'rejected',
                'validation_notes' => 'Artwork file is missing from storage.',
            ]);

            return;
        }

        $details = @getimagesize($absolutePath);
        $dpi = null;
        $notes = [];
        $status = 'processed';

        if (is_array($details)) {
            $width = $details[0] ?? null;
            $height = $details[1] ?? null;
            $xDpi = $details['resolution_x'] ?? null;
            $yDpi = $details['resolution_y'] ?? null;
            $dpi = $xDpi && $yDpi ? round(($xDpi + $yDpi) / 2, 2) : null;

            if ($dpi !== null && $dpi < (float) config('shop.min_dpi', 150)) {
                $status = 'rejected';
                $notes[] = 'Artwork DPI is below the minimum requirement.';
            }

            $artwork->width_px = $width;
            $artwork->height_px = $height;
        } else {
            $notes[] = 'Could not read image dimensions from the file.';
        }

        $artwork->status = $status;
        $artwork->estimated_dpi = $dpi;
        $artwork->validation_notes = implode(' ', $notes) ?: null;
        $artwork->reviewed_at = now();
        $artwork->save();
    }
}
