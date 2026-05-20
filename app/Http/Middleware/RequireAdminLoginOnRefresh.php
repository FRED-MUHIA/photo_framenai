<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminLoginOnRefresh
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isAdmin()) {
            $timeoutMinutes = (int) config('session.admin_idle_timeout', 60);
            $lastActivity = (int) $request->session()->get('admin_last_activity_at', 0);
            $currentTime = time();

            if ($lastActivity > 0 && ($currentTime - $lastActivity) > ($timeoutMinutes * 60)) {
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with('status', 'Admin session expired due to inactivity. Please log in again.');
            }

            $request->session()->put('admin_last_activity_at', $currentTime);
        }

        $response = $next($request);

        // Prevent browser caching of admin pages.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }
}
