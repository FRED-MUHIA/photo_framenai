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
        [480, 70],
        [520, 64],
        [520, 66],
        [560, 64],
        [640, 66],
        [700, 70],
        [900, 70],
        [1100, 70],
        [1200, 80],
        [1200, 66],
        [1600, 70],
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
        $maxDimension = max(120, $maxDimension);
        $quality = min(95, max(40, $quality));
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
            return self::optimizeWithGd($sourcePath, $targetPath, $maxDimension, $quality)
                ? $targetRelativePath : null;
        }

        if (! $process->isSuccessful() || ! is_file($targetPath)) {
            return self::optimizeWithGd($sourcePath, $targetPath, $maxDimension, $quality)
                ? $targetRelativePath : null;
        }

        return $targetRelativePath;
    }

    private static function optimizeWithGd(string $sourcePath, string $targetPath, int $maxDimension, int $quality): bool
    {
        if (! function_exists('imagewebp')) {
            return false;
        }

        $size = @getimagesize($sourcePath);
        // Bound decoding memory on shared hosting; leave large originals untouched.
        if (! $size || $size[0] * $size[1] > 12000000) {
            return false;
        }
        $memoryLimit = trim((string) ini_get('memory_limit'));
        $unit = strtolower(substr($memoryLimit, -1));
        $limitBytes = (int) $memoryLimit * match ($unit) {
            'g' => 1024 ** 3,
            'm' => 1024 ** 2,
            'k' => 1024,
            default => 1,
        };
        if ($limitBytes > 0 && memory_get_usage(true) + $size[0] * $size[1] * 12 + 8388608 > $limitBytes) {
            return false;
        }
        $source = $resized = null;
        $temporary = null;

        try {
            $source = match ($size[2]) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
                default => false,
            };
            if (! $source) {
                return false;
            }
            if ($size[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($sourcePath);
                // Keep orientation-sensitive photographs on the Sharp path.
                if (($exif['Orientation'] ?? 1) != 1) {
                    return false;
                }
            }
            $scale = min(1, $maxDimension / max($size[0], $size[1]));
            $width = max(1, (int) round($size[0] * $scale));
            $height = max(1, (int) round($size[1] * $scale));
            $resized = imagecreatetruecolor($width, $height);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            if (! is_dir(dirname($targetPath)) && ! @mkdir(dirname($targetPath), 0755, true) && ! is_dir(dirname($targetPath))) {
                return false;
            }
            $temporary = tempnam(dirname($targetPath), 'webp-');
            if (! $temporary || ! imagewebp($resized, $temporary, $quality)
                || filesize($temporary) === 0 || filesize($temporary) >= filesize($sourcePath)) {
                return false;
            }
            chmod($temporary, 0644);

            return rename($temporary, $targetPath);
        } catch (Throwable) {
            return false;
        } finally {
            if ($source) {
                imagedestroy($source);
            }
            if ($resized) {
                imagedestroy($resized);
            }
            if ($temporary && is_file($temporary)) {
                @unlink($temporary);
            }
        }
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
