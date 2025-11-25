<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;

class InitializeTenancyBySubdomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = ['cip-tools.de', 'www.cip-tools.de'];

        // Only initialize tenancy for actual subdomains, not central domains
        if (!in_array($host, $centralDomains) && str_ends_with($host, '.cip-tools.de')) {
            try {
                return app(InitializeTenancyByDomain::class)->handle($request, $next);
            } catch (TenantCouldNotBeIdentifiedException $e) {
                // Let the exception handler deal with it
                throw $e;
            }
        }

        // For central domains, skip tenancy initialization
        return $next($request);
    }
}
