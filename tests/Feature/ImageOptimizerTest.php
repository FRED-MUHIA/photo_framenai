<?php

namespace Tests\Feature;

use App\Support\ImageOptimizer;
use Tests\TestCase;

class ImageOptimizerTest extends TestCase
{
    public function test_php_fallback_generates_a_small_webp_and_reuses_it(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP support required.');
        }
        config(['shop.image_optimizer_node_binary' => 'missing-image-optimizer-node']);
        $relative = 'optimizer-test-' . bin2hex(random_bytes(8)) . '.png';
        $source = storage_path('app/public/' . $relative);
        $target = null;
        $image = imagecreatetruecolor(1000, 500);
        imagepng($image, $source, 0);
        imagedestroy($image);

        try {
            $target = ImageOptimizer::optimizePublicPath($relative, 420, 64);
            $this->assertNotNull($target);
            $path = storage_path('app/public/' . $target);
            $size = getimagesize($path);
            $this->assertSame([420, 210], [$size[0], $size[1]]);
            $this->assertSame('image/webp', $size['mime']);
            $this->assertLessThan(filesize($source), filesize($path));
            $this->assertSame($target, ImageOptimizer::optimizedStorageRelativePath($relative, 420, 64));
            $this->assertSame($target, ImageOptimizer::optimizePublicPath($relative, 420, 64));
        } finally {
            @unlink($source);
            if ($target) {
                @unlink(storage_path('app/public/' . $target));
            }
        }
    }
}
