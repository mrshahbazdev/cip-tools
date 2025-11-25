<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Project; // Aapka Custom Project Model

return [
    /**
     * Tenant model used to store tenants.
     * Aapka Project model yahan define kiya gaya hai.
     */
    'tenant_model' => \App\Models\Project::class,

    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => \Stancl\Tenancy\Database\Models\Domain::class,

    /**
     * The list of domains hosting your central app.
     * Central domains par Super Admin login aur landing page chalenge.
     */
    'central_domains' => [
        'cip-tools.de',
        'www.cip-tools.de',
    ],

    /**
     * Tenancy bootstrappers are executed when tenancy is initialized.
     */
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],
    'features' => [
        Stancl\Tenancy\Features\UserImpersonation::class,
        Stancl\Tenancy\Features\TelescopeTags::class,
        Stancl\Tenancy\Features\UniversalRoutes::class,
    ],
    /**
     * Database tenancy config.
     * Hum SQLite use kar rahe hain (local par).
     */
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'sqlite'), // Use DB_CONNECTION from .env

        'template_tenant_connection' => null,
        'prefix' => 'tenant',
        'suffix' => '',

        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    /**
     * Filesystem tenancy config. Har project ki files alag folder mein store hongi.
     */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
            's3',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => true,
    ],

    /**
     * Identification Middleware: Subdomain se Tenant ko pehchanne ke liye.
     */
    'identification_middleware' => [
        'web' => [
            Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ],
        'api' => [
            // API access ke liye
        ],
    ],

    // Baqi settings (Cache, Redis, Features, Routes) default hi rehne dein.
    'routes' => true,
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],
];
