<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckTrialExpiry;
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
        // Sirf InitializeTenancyByDomain use karen, PreventAccessFromCentralDomains nahi
        $middleware->web(append: [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification failed - show 404 ONLY for actual subdomains
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            $host = $request->getHost();
            $centralDomains = ['cip-tools.de', 'www.cip-tools.de'];

            // Only handle actual subdomains, not central domains
            if (!in_array($host, $centralDomains) && str_ends_with($host, '.cip-tools.de')) {
                return response()->view('errors.404', [
                    'message' => "The subdomain '{$host}' does not exist."
                ], 404);
            }

            // For central domains, let the normal flow continue
            return null;
        });
    })->create();
