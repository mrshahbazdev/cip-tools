<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== DEBUG ROUTES ====================
Route::get('/debug', function () {
    $host = request()->getHost();
    $isCentral = in_array($host, ['cip-tools.de', 'www.cip-tools.de']);

    return [
        'host' => $host,
        'is_central_domain' => $isCentral,
        'tenant' => tenant() ? [
            'id' => tenant()->id,
            'name' => tenant()->name,
            'subdomain' => tenant()->subdomain,
        ] : 'No tenant',
        'current_route' => request()->path()
    ];
});

// ==================== CENTRAL DOMAIN ROUTES ====================
// Ye routes sirf cip-tools.de par kaam karenge
Route::domain('cip-tools.de')->group(function () {
    Route::get('/', function () {
        return "MAIN WEBSITE - Central Application | Host: " . request()->getHost();
    })->name('home');

    Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');
});

// ==================== TENANT DOMAIN ROUTES ====================
// Ye routes sirf subdomains par kaam karenge
Route::domain('{tenant}.cip-tools.de')->group(function () {
    Route::get('/', function ($tenant) {
        // Tenant automatically initialize ho chuka hoga middleware se
        if (!tenant()) {
            abort(404, "Tenant '{$tenant}' not found");
        }

        return "TENANT DASHBOARD - " . tenant()->name . " | Host: " . request()->getHost();
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
    $host = request()->getHost();

    if (in_array($host, ['cip-tools.de', 'www.cip-tools.de'])) {
        abort(404, 'Page not found on main website');
    } else {
        $subdomain = str_replace('.cip-tools.de', '', $host);
        abort(404, "Page not found on tenant: {$subdomain}");
    }
});
