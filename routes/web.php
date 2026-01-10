<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckBanned;
use App\Http\Middleware\CheckMaintenance;
use Illuminate\Support\Facades\Route;

// Apply maintenance check to all routes except admin
Route::middleware([CheckMaintenance::class])->group(function () {

    // Home page
    Route::get('/', [BlogController::class, 'home'])->name('home');

    // Blog routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blogs.show');
    Route::get('/search', [BlogController::class, 'search'])->name('blogs.search');

    // Blog creation routes (with ban check)
    Route::middleware([CheckBanned::class])->group(function () {
        Route::get('/post', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('/post', [BlogController::class, 'store'])->name('blogs.store');
    });
});

// Admin routes (protected by auth middleware - you'll need to set this up)
Route::prefix('admin')->name('admin.')->group(function () {

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
});

