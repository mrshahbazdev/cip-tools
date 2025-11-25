<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckTrialExpiry; // Trial Scheduling ke liye
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // Trial Check Command ko rozana chalana
        $schedule->command(CheckTrialExpiry::class)->dailyAt('00:00');
    })
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware stack
        $middleware->web(prepend: [
            // Ye ensure karta hai ke central domain par tenant-specific routes access na hon
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ]);

        $middleware->web(append: [
            // Ye middleware subdomain par tenant ko identify karta hai
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // --- Ghosting Fix (Tenant Identification Failed) ---

        // Tenant identification failed on domain - show 404
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            if ($request->isMethod('get')) {
                return response()->view('errors.404', [
                    'message' => "The subdomain '{$request->getHost()}' does not exist."
                ], 404);
            }
            return response()->json([
                'error' => 'Subdomain not found',
                'message' => 'The requested subdomain does not exist.'
            ], 404);
        });

        // Fallback for general tenant identification errors
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            if ($request->isMethod('get')) {
                // Redirect user to the central domain homepage
                return redirect(\Illuminate\Support\Facades\URL::to('/'));
            }
            return response()->json([
                'error' => 'Tenant not found'
            ], 404);
        });

        // --- End Ghosting Fix ---
    })->create();
