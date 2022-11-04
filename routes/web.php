<?php

use App\Http\Controllers\ComposeController;
use App\Http\Controllers\UsageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::name('compose.')->prefix('/compose')->group(function () {
        Route::get('/', [ComposeController::class, 'show'])->name('show');
        Route::post('/', [ComposeController::class, 'store'])->name('store');
    });
    Route::name('usage.')->prefix('/usage')->group(function () {
        Route::get('/guess', [UsageController::class, 'guess'])->name('guess');
    });
});
