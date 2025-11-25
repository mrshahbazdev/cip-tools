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
        // Use our custom middleware instead of the default one
        $middleware->web(append: [
            \App\Http\Middleware\InitializeTenancyBySubdomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification failed - show 404 for invalid subdomains
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            $host = $request->getHost();

            // Only handle actual subdomains
            if ($host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de')) {
                $subdomain = str_replace('.cip-tools.de', '', $host);
                return response()->view('errors.404', [
                    'message' => "The subdomain '{$subdomain}' does not exist."
                ], 404);
            }

            return null;
        });
    })->create();
