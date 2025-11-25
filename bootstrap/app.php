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
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ]);

        $middleware->web(append: [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification failed on domain - show 404 ONLY for actual subdomains
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            $host = $request->getHost();
            $centralDomains = ['cip-tools.de', 'www.cip-tools.de'];

            // Check if it's actually a subdomain (not central domain)
            if (!in_array($host, $centralDomains) && str_ends_with($host, '.cip-tools.de')) {
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

            // Agar central domain hai toh default behavior use karen
            return null;
        });

        // Fallback for general tenant identification errors
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            $host = $request->getHost();
            $centralDomains = ['cip-tools.de', 'www.cip-tools.de'];

            // Sirf actual subdomains par redirect karen
            if (!in_array($host, $centralDomains) && str_ends_with($host, '.cip-tools.de')) {
                if ($request->isMethod('get')) {
                    return redirect('https://cip-tools.de');
                }
                return response()->json([
                    'error' => 'Tenant not found'
                ], 404);
            }

            return null;
        });
    })->create();
