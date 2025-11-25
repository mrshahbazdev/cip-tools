<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;

// ==================== DEBUG ROUTES ====================
Route::get('/debug-host', function () {
    $host = request()->getHost();
    return [
        'host' => $host,
        'is_subdomain' => $host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de'),
        'is_central' => $host === 'cip-tools.de',
        'tenant' => tenant() ? tenant()->name : 'No tenant',
        'all_server_vars' => [
            'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Not set',
            'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'Not set',
        ]
    ];
});

// ==================== MAIN DOMAIN ROUTES ====================
Route::get('/', function () {
    $host = request()->getHost();
    if ($host === 'cip-tools.de') {
        return "MAIN WEBSITE - Host: " . $host;
    } else {
        return "INVALID SUBDOMAIN ACCESSED - Host: " . $host . " | This should show 404";
    }
});

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

// ==================== CATCH-ALL WITH PROPER TENANCY HANDLING ====================
Route::fallback(function () {
    $host = request()->getHost();

    // Agar subdomain hai lekin tenant nahi mila, toh 404 dikhao
    if ($host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de')) {
        abort(404, "Subdomain '" . str_replace('.cip-tools.de', '', $host) . "' does not exist.");
    }

    // Central domain par normal 404
    abort(404, 'Page not found');
});
