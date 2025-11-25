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
        // Global middleware
        $middleware->web(prepend: [
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ]);

        $middleware->web(append: [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);

        // Middleware groups
        $middleware->group('tenancy', [
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            // 404 error return karen
            return response()->view('errors.404', [
                'message' => 'The subdomain "' . $request->getHost() . '" does not exist.'
            ], 404);
        });
    })->create();
