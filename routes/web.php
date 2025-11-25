<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== MAIN WEBSITE ROUTES ====================
// These will work on cip-tools.de (central domain)
Route::get('/', function () {
    return "MAIN WEBSITE WORKING - Host: " . request()->getHost();
})->name('home');

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

Route::get('/debug', function () {
    return [
        'status' => 'Central App - No Tenancy',
        'host' => request()->getHost(),
        'tenant' => 'None (tenancy not initialized)'
    ];
});

// ==================== TENANT ROUTES ====================
// These routes will use tenancy middleware for subdomains
Route::middleware(['web', 'tenancy'])->group(function () {
    Route::get('/admin', function () {
        return "Tenant Dashboard: " . tenant()->name . " | Domain: " . request()->getHost();
    });

    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');

    Route::get('/tenant-home', function () {
        return "Welcome to " . tenant()->name;
    });
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    abort(404, 'Page not found: ' . request()->path());
});
