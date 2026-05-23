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

            if (self::isPublicPath($path) && (str_starts_with('/' . ltrim($path, '/'), '/storage/') || self::isLocalHost($host))) {
                return asset(ltrim($path, '/')) . self::queryString($parts);
            }

            return $url;
        }

        if (str_starts_with($url, '//')) {
            return $url;
        }

        if (self::isPublicPath($url)) {
            return asset(ltrim($url, '/'));
        }

        return asset(ltrim($url, '/'));
    }

    private static function isLocalHost(string $host): bool
    {
        $appHost = strtolower(parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');

        return in_array($host, ['localhost', '127.0.0.1', '::1'], true)
            || ($appHost !== '' && $host === $appHost);
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
}
