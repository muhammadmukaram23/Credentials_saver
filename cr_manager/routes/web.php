<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\SecureNoteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebugController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth.check'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Credential Routes
    Route::resource('credentials', CredentialController::class);
    Route::get('/credentials/search', [CredentialController::class, 'search'])->name('credentials.search');
    Route::get('/categories/{category}/credentials', [CredentialController::class, 'byCategory'])->name('credentials.by-category');

    // Credit Card Routes
    Route::resource('credit-cards', CreditCardController::class);

    // Secure Note Routes
    Route::resource('secure-notes', SecureNoteController::class);

    // Category Routes
    Route::resource('categories', CategoryController::class);
    
    // Debug Route
    Route::get('/debug/api', [DebugController::class, 'testApi'])->name('debug.api');
});
