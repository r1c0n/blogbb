<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenance
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for admin routes
        if ($request->is('admin/*') || $request->is('admin')) {
            return $next($request);
        }

        if ($this->settingService->isMaintenanceMode()) {
            return response()->view('maintenance', [
                'siteConfig' => $this->settingService->getSiteConfig(),
            ], 503);
        }

        return $next($request);
    }
}
