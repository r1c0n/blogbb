<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Blog;
use App\Services\BlogService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected SettingService $settingService
    ) {}

    /**
     * Display the admin dashboard
     */
    public function index(): View
    {
        $siteConfig = $this->settingService->getSiteConfig();
        $totalBlogs = $this->blogService->getTotalCount();
        $totalBans = Ban::count();
        $recentBlogs = $this->blogService->getRecentBlogs(10);

        return view('admin.index', [
            'siteConfig' => $siteConfig,
            'totalBlogs' => $totalBlogs,
            'totalBans' => $totalBans,
            'recentBlogs' => $recentBlogs,
        ]);
    }

    /**
     * Show site settings form
     */
    public function settings(): View
    {
        $siteConfig = $this->settingService->getSiteConfig();

        return view('admin.settings', [
            'siteConfig' => $siteConfig,
        ]);
    }

    /**
     * Update site settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_description' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'site_image' => 'nullable|url|max:255',
            'site_announcement' => 'nullable|string|max:500',
        ]);

        $this->settingService->updateSiteConfig([
            'name' => $validated['site_name'],
            'description' => $validated['site_description'],
            'url' => $validated['site_url'],
            'image' => $validated['site_image'] ?? '',
            'announcement' => $validated['site_announcement'] ?? '',
        ]);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(): RedirectResponse
    {
        if ($this->settingService->isMaintenanceMode()) {
            $this->settingService->disableMaintenance();
            $message = 'Maintenance mode disabled';
        } else {
            $this->settingService->enableMaintenance();
            $message = 'Maintenance mode enabled';
        }

        return redirect()->route('admin.index')
            ->with('success', $message);
    }

    /**
     * Show ban management
     */
    public function bans(): View
    {
        $bans = Ban::orderBy('created_at', 'desc')->paginate(20);
        $siteConfig = $this->settingService->getSiteConfig();

        return view('admin.bans', [
            'bans' => $bans,
            'siteConfig' => $siteConfig,
        ]);
    }

    /**
     * Ban an IP address
     */
    public function banIp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'reason' => 'nullable|string|max:500',
        ]);

        Ban::banIp($validated['ip'], $validated['reason'] ?? null);

        return redirect()->route('admin.bans')
            ->with('success', 'IP address banned successfully!');
    }

    /**
     * Unban an IP address
     */
    public function unbanIp(string $ip): RedirectResponse
    {
        Ban::unbanIp($ip);

        return redirect()->route('admin.bans')
            ->with('success', 'IP address unbanned successfully!');
    }

    /**
     * Delete a blog post
     */
    public function deleteBlog(int $id): RedirectResponse
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return redirect()->route('admin.index')
                ->with('error', 'Blog post not found');
        }

        $this->blogService->deleteBlog($blog);

        return redirect()->route('admin.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}
