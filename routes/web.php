<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomePageController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess']);
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Home Page Management
    Route::prefix('home-page')->name('home-page.')->group(function () {
        Route::get('/', [HomePageController::class, 'index'])->name('index');
        
        // About Section Routes
        Route::post('/about/update', [HomePageController::class, 'updateAbout'])->name('about.update');
        
        // Banner Management Routes
        Route::prefix('banners')->name('banners.')->group(function () {
            Route::post('/store', [HomePageController::class, 'storeBanner'])->name('store');
            Route::put('/{banner}', [HomePageController::class, 'updateBanner'])->name('update');
            Route::delete('/{banner}', [HomePageController::class, 'destroyBanner'])->name('destroy');
            Route::post('/update-order', [HomePageController::class, 'updateBannerOrder'])->name('update-order');
        });
        
        // Services Selection Routes
        Route::post('/services/update', [HomePageController::class, 'updateServices'])->name('services.update');
        
        // Company Logos Routes
        Route::prefix('logos')->name('logos.')->group(function () {
            Route::post('/store', [HomePageController::class, 'storeLogo'])->name('store');
            Route::put('/{logo}', [HomePageController::class, 'updateLogo'])->name('update');
            Route::delete('/{logo}', [HomePageController::class, 'destroyLogo'])->name('destroy');
            Route::post('/update-order', [HomePageController::class, 'updateLogoOrder'])->name('update-order');
        });
        
        // Legacy routes for backward compatibility (if needed)
        Route::put('/update', [HomePageController::class, 'update'])->name('update');
        Route::delete('/banner/{id}', [HomePageController::class, 'deleteBanner'])->name('banner.delete');
        Route::post('/banner/{id}/toggle', [HomePageController::class, 'toggleBannerStatus'])->name('banner.toggle');
        Route::delete('/logo/{id}', [HomePageController::class, 'deleteCompanyLogo'])->name('logo.delete');
        Route::post('/logo/{id}/toggle', [HomePageController::class, 'toggleCompanyLogoStatus'])->name('logo.toggle');
    });

    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/create', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    });

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});