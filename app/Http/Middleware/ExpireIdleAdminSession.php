<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ExpireIdleAdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isAdmin() && $this->isExpired($request)) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()
                    ->route('login')
                    ->with('status', 'Admin session expired due to inactivity. Please log in again.');
            }
        }

        return $next($request);
    }

    private function isExpired(Request $request): bool
    {
        $lastActivity = (int) $request->session()->get('admin_last_activity_at', 0);

        if ($lastActivity <= 0) {
            return false;
        }

        $timeoutSeconds = max((int) config('session.admin_idle_timeout', 60), 1) * 60;

        return (time() - $lastActivity) > $timeoutSeconds;
    }
}
