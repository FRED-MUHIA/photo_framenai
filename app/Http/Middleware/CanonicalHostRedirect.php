<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalHostRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('production')) {
            return $next($request);
        }

        $canonicalUrl = rtrim((string) config('seo.site_url'), '/');
        $canonicalHost = strtolower((string) parse_url($canonicalUrl, PHP_URL_HOST));
        $canonicalScheme = (string) (parse_url($canonicalUrl, PHP_URL_SCHEME) ?: 'https');

        if ($canonicalHost === '') {
            return $next($request);
        }

        $requestHost = strtolower($request->getHost());
        $isCanonicalHost = $requestHost === $canonicalHost;
        $isCanonicalScheme = $request->getScheme() === $canonicalScheme;

        if ($isCanonicalHost && $isCanonicalScheme) {
            return $next($request);
        }

        $target = $canonicalScheme . '://' . $canonicalHost . $request->getRequestUri();

        return redirect()->away($target, 301);
    }
}
