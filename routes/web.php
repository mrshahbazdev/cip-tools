<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

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
Route::get('/{any}', function ($any) {
    // Check if we're on a subdomain
    $host = request()->getHost();
    $mainDomain = 'cip-tools.de';

    if ($host !== $mainDomain && !str_ends_with($host, '.cip-tools.de')) {
        abort(404, "Subdomain not found: " . $host);
    }

    return "Page not found";
})->where('any', '.*');
