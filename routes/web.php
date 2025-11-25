<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== MAIN WEBSITE ROUTES ====================
Route::get('/', function () {
    return "MAIN WEBSITE - Host: " . request()->getHost() . " | Tenant: " . (tenant() ? 'Yes' : 'No');
})->name('home');

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

Route::get('/debug', function () {
    return [
        'status' => 'Central App',
        'host' => request()->getHost(),
        'tenant_initialized' => tenant() ? 'Yes - ' . tenant()->name : 'No',
        'is_central_domain' => in_array(request()->getHost(), ['cip-tools.de', 'www.cip-tools.de'])
    ];
});

// ==================== TENANT ROUTES ====================
Route::middleware(['web'])->group(function () {
    Route::get('/admin', function () {
        if (!tenant()) {
            abort(404, 'Tenant route accessed without tenant');
        }
        return "Tenant Dashboard: " . tenant()->name;
    });

    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    abort(404, 'Page not found: ' . request()->path());
});
