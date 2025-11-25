<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== CENTRAL DOMAIN ROUTES ====================
Route::domain('cip-tools.de')->group(function () {
    Route::get('/', function () {
        return "MAIN WEBSITE - Central Application";
    })->name('home');

    Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');
});

// ==================== TENANT DOMAIN ROUTES ====================
Route::domain('{tenant}.cip-tools.de')->group(function () {
    Route::get('/', function ($tenant) {
        if (!tenant()) {
            abort(404, "Tenant '{$tenant}' not found");
        }
        return "TENANT DASHBOARD - " . tenant()->name;
    })->name('tenant.home');

    Route::get('/admin', function ($tenant) {
        if (!tenant()) {
            abort(404, "Tenant not found");
        }
        return "Tenant Admin Panel: " . tenant()->name;
    })->name('tenant.admin');

    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('tenant.payment');
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    abort(404, 'Page not found');
});
