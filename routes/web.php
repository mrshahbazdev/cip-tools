<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

// --- 1. Central Domain Routes (ONLY cip-tools.de) ---
// Registration aur Landing page ke liye.
Route::domain('cip-tools.de')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Registration routes
    Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

    // Test route for central app verification
    Route::get('/test-tenancy-central', function () {
        return "No tenant initialized - Central app running on: " . request()->getHost();
    });
});

// --- 2. Tenant Domain Routes (FOR ALL SUBDOMAINS) ---
// Payment, Dashboard aur Innovation features ke liye.
Route::middleware('web')->domain('{tenant}.cip-tools.de')->group(function () {

    // Default tenant homepage
    Route::get('/', function ($tenant) {
        $currentTenant = tenant();
        if ($currentTenant) {
            // Yahan se aap tenant ke dashboard par redirect kar sakte hain
            return redirect()->route('tenant.dashboard');
        }
        abort(404, "Tenant not found");
    });

    // Tenant Dashboard
    Route::get('/admin', function ($tenant) {
        $currentTenant = tenant();
        return "Tenant Dashboard: " . $currentTenant->name;
    })->name('tenant.dashboard');

    // Payment/Monetization routes
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');
    Route::post('/invoice', [PaymentController::class, 'generateInvoice'])->name('payment.invoice');
});

// --- 3. Fallback (Ghosting handled by bootstrap/app.php) ---
// Fallback ko ab bilkul simple rakhen, kyunki humne iski logic bootstrap/app.php mein daal di hai.
// Ye sirf woh requests pakdega jo kisi route se match nahi hoti.
Route::fallback(function () {
    abort(404);
});
// Test route to check what's happening
Route::get('/debug-host', function () {
    $host = request()->getHost();
    $tenant = tenant();

    return [
        'host' => $host,
        'tenant' => $tenant ? $tenant->name : 'No tenant',
        'is_central_domain' => $host === 'cip-tools.de',
        'is_subdomain' => str_ends_with($host, '.cip-tools.de') && $host !== 'cip-tools.de'
    ];
});
