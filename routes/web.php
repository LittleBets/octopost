<?php

use App\Http\Controllers\CompositionController;
use App\Http\Controllers\CompositionResultChoiceController;
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
    Route::name('composition.')->prefix('/composition')->group(function () {
        Route::get('/', [CompositionController::class, 'showAll'])->name('showAll');
        Route::get('/{composition_id}', [CompositionController::class, 'showOne'])->name('showOne');
        Route::post('/', [CompositionController::class, 'store'])->name('store');
        Route::patch('/{composition_id}', [CompositionController::class, 'update'])->name('update');
    });
    Route::name('compose.')->prefix('/compose')->group(function () {
        Route::get('/', [CompositionController::class, 'compose'])->name('new');
    });
    Route::name('composition-result-choice.')->prefix('/composition-result-choice')->group(function () {
        Route::patch('/{composition_result_choice_id}', [CompositionResultChoiceController::class, 'update'])->name('update');
        Route::delete('/{composition_result_choice_id}', [CompositionResultChoiceController::class, 'destroy'])->name('destroy');
    });
    Route::name('usage.')->prefix('/usage')->group(function () {
        Route::get('/guess', [UsageController::class, 'guess'])->name('guess');
    });
});
