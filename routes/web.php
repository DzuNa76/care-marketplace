<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Provider\EditProfile;
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

        Route::livewire(
                '/provider/services',
                'provider.my-services'
            )->name('provider.services.index');

        Route::livewire(
                '/provider/services/create',
                'provider.create-service'
            )->name('provider.services.create');

            Route::livewire(
                '/provider/services/{service}/edit',
                'provider.edit-service'
            )->name('provider.services.edit');

        Route::livewire(
            '/provider/verification-documents',
            UploadVerificationDocument::class
        )->name('provider.verification-documents');

        Route::livewire(
            '/provider/profile/edit',
            EditProfile::class
        )->name('provider.profile.edit');
    });
});
