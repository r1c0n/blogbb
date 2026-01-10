<?php

namespace App\Http\Middleware;

use App\Models\Ban;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $this->getClientIp($request);

        if (Ban::isBanned($ip)) {
            abort(403, 'Your IP address has been banned from posting.');
        }

        return $next($request);
    }

    /**
     * Get the client IP address
     */
    protected function getClientIp(Request $request): string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]);
        }

        return $request->ip() ?? '0.0.0.0';
    }
}
