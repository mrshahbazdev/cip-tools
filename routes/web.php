<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;
use App\Http\Controllers\PaymentController;
// Main domain routes - only for cip-tools.de
Route::middleware('web')->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');
    Route::post('/invoice', [PaymentController::class, 'generateInvoice'])->name('payment.invoice');
});
Route::domain('cip-tools.de')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

    Route::get('/test-tenancy', function () {
        try {
            $tenant = tenant();
            if ($tenant) {
                return "Tenant is initialized: " . $tenant->name;
            } else {
                return "No tenant initialized - Central app";
            }
        } catch (TenantCouldNotBeIdentifiedException $e) {
            return "Tenant not found - should show 404";
        }
    });
});

// Tenant routes - for all subdomains of cip-tools.de
Route::domain('{tenant}.cip-tools.de')->group(function () {
    Route::get('/', function ($tenant) {
        try {
            // Tenant automatically initialize ho jayega middleware se
            $currentTenant = tenant();
            if ($currentTenant) {
                return "Tenant Dashboard: " . $currentTenant->name;
            }
            abort(404, "Tenant not found");
        } catch (TenantCouldNotBeIdentifiedException $e) {
            abort(404, "Subdomain '{$tenant}' does not exist");
        }
    });

    // Yahan aap tenant-specific routes add kar sakte hain
    Route::get('/dashboard', function ($tenant) {
        $currentTenant = tenant();
        return "Dashboard for: " . $currentTenant->name;
    });
});

// Catch-all route for invalid subdomains
Route::fallback(function () {
    $host = request()->getHost();
    $mainDomain = 'cip-tools.de';

    // Agar subdomain hai lekin exist nahi karta
    if ($host !== $mainDomain && str_ends_with($host, '.cip-tools.de')) {
        $subdomain = str_replace('.cip-tools.de', '', $host);
        abort(404, "Subdomain '{$subdomain}' does not exist.");
    }

    // Agar koi aur invalid route
    abort(404, "Page not found");
});
