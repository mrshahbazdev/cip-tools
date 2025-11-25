<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckTrialExpiry;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(CheckTrialExpiry::class)->dailyAt('00:00');
    })
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware stack
        $middleware->web(prepend: [
            // Sirf tenant domains par hi ye middleware apply karen
        ]);

        $middleware->web(append: [
            // Ye middleware subdomain par tenant ko identify karta hai
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification failed on domain - show 404
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            $host = $request->getHost();

            // Check if it's actually a subdomain (not main domain)
            if ($host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de')) {
                if ($request->isMethod('get')) {
                    return response()->view('errors.404', [
                        'message' => "The subdomain '{$host}' does not exist."
                    ], 404);
                }
                return response()->json([
                    'error' => 'Subdomain not found',
                    'message' => 'The requested subdomain does not exist.'
                ], 404);
            }

            // Agar main domain hai toh default behavior use karen
            return null;
        });

        // Fallback for general tenant identification errors
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            $host = $request->getHost();

            // Sirf subdomains par redirect karen, main domain par nahi
            if ($host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de')) {
                if ($request->isMethod('get')) {
                    return redirect(\Illuminate\Support\Facades\URL::to('/'));
                }
                return response()->json([
                    'error' => 'Tenant not found'
                ], 404);
            }

            return null;
        });
    })->create();
