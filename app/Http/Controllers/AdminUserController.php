<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    public function __construct(
        protected SettingService $settingService) {}

    public function index(): View
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());
        $adminUsers = AdminUser::orderBy('created_at', 'desc')->get();
        $siteConfig = $this->settingService->getSiteConfig();

        return view('admin.users.index', [
            'adminUsers' => $adminUsers,
            'siteConfig' => $siteConfig,
        ]);
    }

    public function create(): View
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());

        $siteConfig = $this->settingService->getSiteConfig();

        return view('admin.users.create', [
            'siteConfig' => $siteConfig,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admin_users',
            'password' => 'required|string|min:6',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:admin',
        ]);

        AdminUser::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user created successfully!');
    }

    public function edit(AdminUser $adminUser): View
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());

        $siteConfig = $this->settingService->getSiteConfig();

        return view('admin.users.edit', [
            'adminUser' => $adminUser,
            'siteConfig' => $siteConfig,
        ]);
    }

    public function updatePassword(Request $request, AdminUser $adminUser): RedirectResponse
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());

        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (Hash::check($validated['password'], $adminUser->password)) {
            return back()->withErrors([
                'password' => 'New password must be different from current password.',
            ]);
        }

        $adminUser->password = Hash::make($validated['password']);
        $adminUser->must_change_password = $request->boolean('force_change');
        $adminUser->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Password updated successfully!');
    }

    public function destroy(AdminUser $adminUser): RedirectResponse
    {
        $this->authorize('manageAdmins', Auth::guard('admin')->user());

        if ($adminUser->isOwner()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete the owner account.');
        }

        if ($adminUser->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account.');
        }

        $adminUser->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully!');
    }
}
