<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Yahan aap apne custom middleware register kar sakte hain
        $middleware->web(append: [
            // Agar aap custom tenant handling middleware banayein toh yahan add karen
        ]);

        // Tenancy middleware group (agar needed ho)
        $middleware->group('tenancy', [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tenant identification fail hone par redirect karen
        $exceptions->renderable(function (TenantCouldNotBeIdentifiedException $e, $request) {
            if ($request->isMethod('get')) {
                // Production mein redirect karen, local par error dikhayein
                if (!app()->environment('local')) {
                    $mainDomain = config('app.url', 'https://cip-tools.de');
                    return redirect($mainDomain);
                }
            }

            // API requests ke liye JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Tenant not found',
                    'message' => 'The requested domain could not be identified.'
                ], 404);
            }

            // Local environment mein default error dikhayein
            return null;
        });
    })->create();
