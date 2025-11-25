<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

// Debug routes
Route::get('/debug-server', function () {
    return [
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'Not set',
        'http_host' => $_SERVER['HTTP_HOST'] ?? 'Not set',
        'request_host' => request()->getHost(),
        'all_headers' => request()->header(),
    ];
});

Route::get('/debug-tenancy', function () {
    $config = config('tenancy');

    return [
        'central_domains' => $config['central_domains'] ?? 'Not set',
        'current_host' => request()->getHost(),
        'is_central_domain' => in_array(request()->getHost(), $config['central_domains'] ?? []),
        'tenant_model' => $config['tenant_model'] ?? 'Not set',
    ];
});

// Main website routes
Route::get('/', function () {
    return "MAIN WEBSITE WORKING - Host: " . request()->getHost();
});

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');

Route::get('/test', function () {
    return "TEST PAGE - Host: " . request()->getHost();
});
