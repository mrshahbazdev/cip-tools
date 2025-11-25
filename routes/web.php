<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenantHomeController;
// ==================== CENTRAL DOMAIN ROUTES ====================
Route::domain('cip-tools.de')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');
});

// ==================== TENANT DOMAIN ROUTES ====================
Route::domain('{tenant}.cip-tools.de')->group(function () {
    Route::get('/', [TenantHomeController::class, 'index'])->name('tenant.home');

    Route::get('/admin', function () { // $tenant parameter ko hata diya
        $project = tenant();
        if (!$project) {
            abort(404, "Tenant not found");
        }
        // Return the new admin view
        return view('tenant.admin', ['project' => $project]); 
    })->name('tenant.admin');

    // Payment/Monetization routes (Requires tenant identification)
        Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('tenant.payment.form');
        Route::post('/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');
        Route::post('/invoice', [PaymentController::class, 'generateInvoice'])->name('payment.invoice');
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    abort(404, 'Page not found');
});
