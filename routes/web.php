<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Provider\UploadVerificationDocument;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:provider'])->group(function () {
    Route::get(
        '/provider/verification-documents',
        UploadVerificationDocument::class
    )->name('provider.verification-documents');
});
