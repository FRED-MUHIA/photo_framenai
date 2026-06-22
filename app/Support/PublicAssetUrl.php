<?php

namespace App\Support;

class PublicAssetUrl
{
    public static function resolve(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (preg_match('/^(data:|mailto:|tel:|#)/i', $url)) {
            return $url;
        }

        if (preg_match('/^https?:\/\//i', $url)) {
            $parts = parse_url($url);
            $host = strtolower($parts['host'] ?? '');
            $path = $parts['path'] ?? '';

            if (self::isPrivateNetworkHost($host)) {
                return self::isPublicPath($path)
                    ? self::assetForPublicPath($path) . self::queryString($parts)
                    : null;
            }

            if (self::isPublicPath($path) && (str_starts_with('/' . ltrim($path, '/'), '/storage/') || self::isLocalHost($host))) {
                return self::assetForPublicPath($path) . self::queryString($parts);
            }

            return $url;
        }

        if (str_starts_with($url, '//')) {
            return $url;
        }

        if (self::isPublicPath($url)) {
            return self::assetForPublicPath($url);
        }

        return asset(ltrim($url, '/'));
    }

    public static function image(?string $url, ?int $width = null, ?int $quality = null): ?string
    {
        $resolvedUrl = self::resolve($url);

        if (! $resolvedUrl || ! preg_match('/^https?:\/\//i', $resolvedUrl)) {
            return $resolvedUrl;
        }

        $parts = parse_url($resolvedUrl);
        $host = strtolower($parts['host'] ?? '');

        if ($host !== 'images.unsplash.com') {
            return $resolvedUrl;
        }

        parse_str($parts['query'] ?? '', $query);

        $query['auto'] = $query['auto'] ?? 'format';
        $query['fit'] = $query['fit'] ?? 'crop';

        if ($width) {
            $query['w'] = max(120, $width);
        }

        if ($quality) {
            $query['q'] = min(90, max(40, $quality));
        }

        $path = $parts['path'] ?? '';
        $rebuilt = ($parts['scheme'] ?? 'https') . '://' . $host . $path;
        $queryString = http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        return $queryString ? $rebuilt . '?' . $queryString : $rebuilt;
    }

    private static function isLocalHost(string $host): bool
    {
        $appHost = strtolower(parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');

        return in_array($host, ['localhost', '127.0.0.1', '::1'], true)
            || ($appHost !== '' && $host === $appHost);
    }

    private static function isPrivateNetworkHost(string $host): bool
    {
        $host = trim(strtolower($host), '[]');

        if (in_array($host, ['localhost', 'localhost.localdomain', '0.0.0.0', '::1'], true)) {
            return true;
        }

        if (! filter_var($host, FILTER_VALIDATE_IP)) {
            return false;
        }

        return filter_var(
            $host,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    private static function isPublicPath(string $path): bool
    {
        $path = '/' . ltrim($path, '/');

        return str_starts_with($path, '/storage/')
            || str_starts_with($path, '/build/')
            || str_starts_with($path, '/css/');
    }

    private static function queryString(array $parts): string
    {
        return isset($parts['query']) ? '?' . $parts['query'] : '';
    }

    private static function assetForPublicPath(string $path): string
    {
        $normalizedPath = '/' . ltrim($path, '/');
        $assetPath = ltrim($normalizedPath, '/');

        if (str_starts_with($normalizedPath, '/storage/')) {
            $relativePath = substr($normalizedPath, strlen('/storage/'));
            $optimizedPath = ImageOptimizer::optimizedStorageRelativePath($relativePath);

            if ($optimizedPath) {
                $assetPath = 'storage/' . $optimizedPath;
            }
        }

        return asset($assetPath);
    }
}
