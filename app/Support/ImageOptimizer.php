<?php

namespace App\Support;

use Symfony\Component\Process\Process;
use Throwable;

class ImageOptimizer
{
    private const CACHE_DIRECTORY = 'optimized-images';
    private const DEFAULT_MAX_DIMENSION = 1600;
    private const DEFAULT_QUALITY = 78;
    private const OPTIMIZABLE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const COMMON_VARIANTS = [
        [180, 60],
        [360, 62],
        [420, 64],
        [520, 64],
        [560, 64],
        [640, 66],
        [700, 70],
        [1100, 70],
        [1200, 80],
        [1600, 78],
    ];

    public static function optimizedStorageRelativePath(string $relativePath, ?int $maxDimension = null, ?int $quality = null): ?string
    {
        $relativePath = self::normalizeRelativePath($relativePath);

        if (! self::isOptimizable($relativePath)) {
            return null;
        }

        $sourcePath = self::sourcePath($relativePath);

        if (! is_file($sourcePath)) {
            return null;
        }

        $targetPath = self::targetPath($relativePath, $sourcePath, $maxDimension, $quality);

        if (is_file($targetPath)) {
            return self::targetRelativePath($relativePath, $sourcePath, $maxDimension, $quality);
        }

        $defaultTargetPath = self::targetPath($relativePath, $sourcePath);

        return is_file($defaultTargetPath)
            ? self::targetRelativePath($relativePath, $sourcePath)
            : null;
    }

    public static function optimizePublicPath(string $relativePath, int $maxDimension = 1600, int $quality = 78): ?string
    {
        $relativePath = self::normalizeRelativePath($relativePath);

        if (! self::isOptimizable($relativePath)) {
            return null;
        }

        $sourcePath = self::sourcePath($relativePath);

        if (! is_file($sourcePath)) {
            return null;
        }

        $targetRelativePath = self::targetRelativePath($relativePath, $sourcePath, $maxDimension, $quality);
        $targetPath = storage_path('app/public/' . $targetRelativePath);

        if (is_file($targetPath)) {
            return $targetRelativePath;
        }

        $scriptPath = base_path('scripts/optimize-image.mjs');

        if (! is_file($scriptPath)) {
            return null;
        }

        try {
            $process = new Process([
                self::nodeBinary(),
                $scriptPath,
                $sourcePath,
                $targetPath,
                (string) $maxDimension,
                (string) $quality,
            ], base_path());
            $process->setTimeout(60);
            $process->run();
        } catch (Throwable) {
            @unlink($targetPath);

            return null;
        }

        if (! $process->isSuccessful() || ! is_file($targetPath)) {
            @unlink($targetPath);

            return null;
        }

        return $targetRelativePath;
    }

    public static function optimizeCommonPublicVariants(string $relativePath, ?array $extraVariants = null): array
    {
        $variants = self::uniqueVariants([
            ...($extraVariants ?? []),
            ...self::COMMON_VARIANTS,
        ]);

        $optimizedPaths = [];

        foreach ($variants as [$maxDimension, $quality]) {
            $optimizedPath = self::optimizePublicPath($relativePath, $maxDimension, $quality);

            if ($optimizedPath) {
                $optimizedPaths[] = $optimizedPath;
            }
        }

        return array_values(array_unique($optimizedPaths));
    }

    private static function normalizeRelativePath(string $relativePath): string
    {
        $relativePath = str_replace('\\', '/', $relativePath);
        $relativePath = preg_replace('#^/?storage/#', '', $relativePath) ?? $relativePath;

        return ltrim($relativePath, '/');
    }

    private static function isOptimizable(string $relativePath): bool
    {
        if ($relativePath === '' || str_starts_with($relativePath, self::CACHE_DIRECTORY . '/')) {
            return false;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

        return in_array($extension, self::OPTIMIZABLE_EXTENSIONS, true);
    }

    private static function sourcePath(string $relativePath): string
    {
        return storage_path('app/public/' . $relativePath);
    }

    private static function targetPath(string $relativePath, string $sourcePath, ?int $maxDimension = null, ?int $quality = null): string
    {
        return storage_path('app/public/' . self::targetRelativePath($relativePath, $sourcePath, $maxDimension, $quality));
    }

    private static function targetRelativePath(string $relativePath, string $sourcePath, ?int $maxDimension = null, ?int $quality = null): string
    {
        $directory = trim(str_replace('\\', '/', pathinfo($relativePath, PATHINFO_DIRNAME)), '.');
        $filename = pathinfo($relativePath, PATHINFO_FILENAME);
        $fingerprint = substr(sha1($relativePath . '|' . filesize($sourcePath) . '|' . filemtime($sourcePath)), 0, 12);
        $targetDirectory = self::CACHE_DIRECTORY . ($directory ? '/' . $directory : '');
        $variant = '';

        $normalizedMaxDimension = max(120, (int) ($maxDimension ?: self::DEFAULT_MAX_DIMENSION));
        $normalizedQuality = min(95, max(40, (int) ($quality ?: self::DEFAULT_QUALITY)));

        if ($normalizedMaxDimension !== self::DEFAULT_MAX_DIMENSION || $normalizedQuality !== self::DEFAULT_QUALITY) {
            $variant = '-w' . $normalizedMaxDimension . '-q' . $normalizedQuality;
        }

        return $targetDirectory . '/' . $filename . '-' . $fingerprint . $variant . '.webp';
    }

    private static function uniqueVariants(array $variants): array
    {
        $unique = [];

        foreach ($variants as $variant) {
            $maxDimension = max(120, (int) ($variant[0] ?? self::DEFAULT_MAX_DIMENSION));
            $quality = min(95, max(40, (int) ($variant[1] ?? self::DEFAULT_QUALITY)));
            $key = $maxDimension . ':' . $quality;

            $unique[$key] = [$maxDimension, $quality];
        }

        return array_values($unique);
    }

    private static function nodeBinary(): string
    {
        return (string) config('shop.image_optimizer_node_binary', env('IMAGE_OPTIMIZER_NODE_BINARY', 'node'));
    }
}
