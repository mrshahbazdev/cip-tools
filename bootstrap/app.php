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
        // Sirf InitializeTenancyByDomain middleware use karen
        $middleware->web(append: [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Simple exception handling
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            $host = $request->getHost();

            // Sirf actual subdomains ke liye 404 dikhayen
            if ($host !== 'cip-tools.de' && str_ends_with($host, '.cip-tools.de')) {
                return response()->view('errors.404', [
                    'message' => "The subdomain '{$host}' does not exist."
                ], 404);
            }

            return null;
        });
    })->create();
