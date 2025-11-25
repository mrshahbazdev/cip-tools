<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== MAIN WEBSITE ROUTES ====================
// These will work on cip-tools.de (central domain)
Route::get('/', function () {
    return "MAIN WEBSITE - Host: " . request()->getHost() . " | Tenant: " . (tenant() ? tenant()->name : 'None');
});

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

// Debug routes
Route::get('/debug', function () {
    return [
        'status' => 'Central App',
        'host' => request()->getHost(),
        'tenant' => tenant() ? tenant()->name : 'No tenant',
        'is_central' => in_array(request()->getHost(), ['cip-tools.de', 'www.cip-tools.de'])
    ];
});

// ==================== TENANT ROUTES ====================
// These will automatically work when tenant is initialized
Route::middleware(['web'])->group(function () {
    // Tenant dashboard (only works when tenant exists)
    Route::get('/admin', function () {
        if (!tenant()) {
            abort(404, 'This route is only available for tenants');
        }
        return "Tenant Dashboard: " . tenant()->name;
    });

    // Tenant payment routes
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    $host = request()->getHost();

    if (in_array($host, ['cip-tools.de', 'www.cip-tools.de'])) {
        abort(404, 'Page not found on main website');
    } else {
        abort(404, 'Page not found on tenant: ' . $host);
    }
});
