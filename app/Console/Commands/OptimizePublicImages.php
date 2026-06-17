<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class OptimizePublicImages extends Command
{
    protected $signature = 'images:optimize-public {--max-dimension=1600} {--quality=78}';

    protected $description = 'Create optimized WebP sidecars for images in storage/app/public.';

    public function handle(): int
    {
        $root = storage_path('app/public');

        if (! is_dir($root)) {
            $this->warn('Public storage directory does not exist.');

            return self::SUCCESS;
        }

        $maxDimension = max(320, (int) $this->option('max-dimension'));
        $quality = min(95, max(40, (int) $this->option('quality')));
        $processed = 0;
        $optimized = 0;
        $skipped = 0;

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile()) {
                continue;
            }

            $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));

            if (str_starts_with($relativePath, 'optimized-images/')) {
                continue;
            }

            $processed++;

            if (ImageOptimizer::optimizePublicPath($relativePath, $maxDimension, $quality)) {
                $optimized++;
            } else {
                $skipped++;
            }
        }

        $this->info("Processed {$processed} file(s). Optimized {$optimized}. Skipped {$skipped}.");

        return self::SUCCESS;
    }
}
