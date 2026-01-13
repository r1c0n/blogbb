<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordChangeController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function show(): View
    {
        $siteConfig = $this->settingService->getSiteConfig();
        
        $breadcrumbs = [
            ['name' => 'Change Password', 'url' => null]
        ];

        return view('auth.change-password', [
            'siteConfig' => $siteConfig,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::guard('admin')->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        if (Hash::check($validated['new_password'], $user->password)) {
            return back()->withErrors([
                'new_password' => 'New password must be different from current password.',
            ]);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('admin.index')
            ->with('success', 'Password changed successfully!');
    }
}
