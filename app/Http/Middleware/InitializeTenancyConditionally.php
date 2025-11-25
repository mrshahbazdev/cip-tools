<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancyConditionally
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = ['cip-tools.de', 'www.cip-tools.de'];

        // Only initialize tenancy for subdomains, not central domains
        if (!in_array($host, $centralDomains) && str_ends_with($host, '.cip-tools.de')) {
            return app(InitializeTenancyByDomain::class)->handle($request, $next);
        }

        // For central domains, skip tenancy initialization
        return $next($request);
    }
}
