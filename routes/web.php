<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== CENTRAL DOMAIN ROUTES ====================
// These routes will ONLY work on cip-tools.de and www.cip-tools.de
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

// Debug route to check tenancy status
Route::get('/debug', function () {
    return [
        'status' => 'Central Application',
        'host' => request()->getHost(),
        'tenant' => tenant() ? tenant()->name : 'No tenant initialized',
        'is_central_domain' => in_array(request()->getHost(), ['cip-tools.de', 'www.cip-tools.de'])
    ];
});

// ==================== TENANT ROUTES ====================
// These routes will ONLY work when a tenant is initialized (on subdomains)
Route::middleware(['web'])->group(function () {
    // Tenant dashboard
    Route::get('/admin', function () {
        if (!tenant()) {
            abort(404, 'This route is only available for tenants');
        }
        return "Tenant Dashboard: " . tenant()->name . " | Domain: " . request()->getHost();
    })->name('tenant.dashboard');

    // Tenant payment routes
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');

    // Tenant homepage
    Route::get('/home', function () {
        if (!tenant()) {
            abort(404, 'Tenant not found');
        }
        return "Welcome to " . tenant()->name . " | " . request()->getHost();
    });
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    $host = request()->getHost();

    // Different messages for central vs tenant domains
    if (in_array($host, ['cip-tools.de', 'www.cip-tools.de'])) {
        abort(404, 'Page not found on central domain');
    } else {
        abort(404, 'Page not found on tenant domain: ' . $host);
    }
});
