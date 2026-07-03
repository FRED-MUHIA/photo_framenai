<?php

namespace App\Http\Middleware;

use App\Support\StorefrontCache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshStorefrontAfterAdminChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethodSafe() && $response->getStatusCode() < 400) {
            StorefrontCache::refresh();
        }

        return $response;
    }
}
