<?php

use App\Http\Controllers\ExtrairGtin\DownloadExcelFileGtinController;
use App\Http\Controllers\ExtrairGtin\ExtractGtinCodeNfeController;
use App\Http\Controllers\ExtrairGtin\UploadNfeController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::prefix('extrair-gtin')->group(function () {

        Route::get('/upload', [UploadNfeController::class, 'index'])
            ->name('extractGtin.index');

        Route::post('/upload', [UploadNfeController::class, 'upload'])
            ->name('extractGtin.upload');

        Route::get('/extrair-codigo-gtin', ExtractGtinCodeNfeController::class)
            ->name('processNfeGtinCode.process');

        Route::get('/download/{token}', DownloadExcelFileGtinController::class)->name('extractGtin.download');
    });
});

require __DIR__ . '/auth.php';
