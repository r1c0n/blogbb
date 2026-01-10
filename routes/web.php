<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Middleware\CheckBanned;
use App\Http\Middleware\CheckMaintenance;
use App\Http\Middleware\RequirePasswordChange;
use Illuminate\Support\Facades\Route;

// Apply maintenance check to all routes except admin
Route::middleware([CheckMaintenance::class])->group(function () {

    // Home page
    Route::get('/', [BlogController::class, 'home'])->name('home');

    // Credits page
    Route::get('/credits', [BlogController::class, 'credits'])->name('credits');

    // Blog routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blogs.show');
    Route::get('/search', [BlogController::class, 'search'])->name('blogs.search');

    // Blog creation routes (admin only)
    Route::get('/post', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/post', [BlogController::class, 'store'])->name('blogs.store');
});

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Password change (requires auth but not password change)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/change-password', [PasswordChangeController::class, 'show'])->name('password.show');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.update');
    });
});

// Admin routes (protected by auth middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', RequirePasswordChange::class])->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Maintenance mode toggle
    Route::post('/maintenance/toggle', [AdminController::class, 'toggleMaintenance'])->name('maintenance.toggle');

    // Ban management
    Route::get('/bans', [AdminController::class, 'bans'])->name('bans');
    Route::post('/bans', [AdminController::class, 'banIp'])->name('bans.store');
    Route::delete('/bans/{ip}', [AdminController::class, 'unbanIp'])->name('bans.destroy');

    // Blog management
    Route::delete('/blogs/{id}', [AdminController::class, 'deleteBlog'])->name('blogs.destroy');

    // Admin user management (owner only)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{adminUser}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{adminUser}/password', [AdminUserController::class, 'updatePassword'])->name('users.password');
    Route::delete('/users/{adminUser}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

