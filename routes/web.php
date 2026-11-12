<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Provider\EditProfile;
use App\Livewire\Provider\UploadVerificationDocument;
use Illuminate\Support\Facades\Route;
use App\Models\ProviderVerificationDocument;
use Illuminate\Support\Facades\Storage;

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
            '/provider/services/{service}/prices',
            'provider.manage-service-prices'
        )->name('provider.services.prices.index');

        Route::livewire(
            '/provider/services/{service}/prices/create',
            'provider.create-service-price'
        )->name('provider.services.prices.create');

        Route::livewire(
            '/provider/services/{service}/prices/create',
            'provider.create-service-price'
        )->name('provider.services.prices.create');

        Route::livewire(
            '/provider/services/{service}/prices/{price}/edit',
            'provider.edit-service-price'
        )->name('provider.services.prices.edit');

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

Route::get(
    '/admin/provider-verification-documents/{document}/preview',
    function (ProviderVerificationDocument $document) {
        abort_unless(
            Storage::disk('provider_documents')->exists($document->file_path),
            404
        );

        return response()->file(
            Storage::disk('provider_documents')->path($document->file_path),
            [
                'Content-Disposition' => 'inline; filename="' .
                    basename($document->original_filename) . '"',
            ]
        );
    }
)->middleware(['auth', 'role:admin'])
    ->name('admin.provider-verification-documents.preview');
