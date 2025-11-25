<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== MAIN WEBSITE ROUTES ====================
// These work on cip-tools.de (central domain)
Route::get('/', function () {
    return view('welcome'); // Your main website view
})->name('home');

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

// ==================== TENANT ROUTES ====================
// These automatically work when tenant is initialized (valid subdomains)
Route::middleware(['web'])->group(function () {
    // Tenant dashboard
    Route::get('/admin', function () {
        if (!tenant()) {
            abort(404, 'Tenant not found');
        }
        return "Tenant Dashboard: " . tenant()->name;
    })->name('tenant.dashboard');

    // Tenant payment routes
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');

    // Tenant homepage
    Route::get('/home', function () {
        if (!tenant()) {
            abort(404, 'Tenant not found');
        }
        return "Welcome to " . tenant()->name;
    });
});

// ==================== CATCH-ALL ROUTE ====================
Route::fallback(function () {
    $host = request()->getHost();

    if ($host === 'cip-tools.de') {
        abort(404, 'Page not found on main website');
    } else {
        abort(404, 'Page not found');
    }
});
