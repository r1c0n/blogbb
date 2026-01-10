<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            // If user must change password and not already on change password page
            if ($user->must_change_password && !$request->routeIs('admin.password.show', 'admin.password.update', 'admin.logout')) {
                return redirect()->route('admin.password.show')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
