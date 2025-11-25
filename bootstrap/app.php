<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware configuration
        $middleware->web(append: [
            // Agar custom middleware chahiye toh yahan add karen
        ]);

        // Tenancy middleware group
        $middleware->group('tenancy', [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification fail hone par 404 error dikhayein
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            // Simple 404 page return karen
            if ($request->isMethod('get')) {
                return response()->view('errors.404', [], 404);
            }

            // API requests ke liye JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Tenant not found',
                    'message' => 'The requested subdomain does not exist.'
                ], 404);
            }

            // Default 404 response
            abort(404, 'Subdomain not found');
        });
    })->create();
