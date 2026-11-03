<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Provider\UploadVerificationDocument;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('role:customer')->group(function () {
        Route::view('/customer/dashboard', 'customer.dashboard')
            ->name('customer.dashboard');
    });

    Route::middleware('role:provider')->group(function () {
        Route::view('/provider/dashboard', 'provider.dashboard')
            ->name('provider.dashboard');

        Route::get(
            '/provider/verification-documents',
            UploadVerificationDocument::class
        )->name('provider.verification-documents');
    });
});
