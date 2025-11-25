<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedException;
use Stancl\Tenancy\Facades\Tenancy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tenant identification failed hone par home page par redirect karein
        Tenancy::identifyTenantOnFail(function (TenantCouldNotBeIdentifiedException $exception) {
            // User ko main domain ke landing page par bhej dein
            return redirect(URL::to('/'));
        });
    }
}
