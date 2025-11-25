<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
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
        // Tenant identification failed - show 404 instead of 500
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

        // Fallback for other tenant identification errors
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            if ($request->isMethod('get')) {
                return response()->view('errors.404', [
                    'message' => 'Tenant could not be identified.'
                ], 404);
            }

            return response()->json([
                'error' => 'Tenant not found'
            ], 404);
        });
    })->create();
