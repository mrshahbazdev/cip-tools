<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Project; // Aapka Custom Project Model

return [
    /**
     * Tenant model used to store tenants.
     * Aapka Project model yahan define kiya gaya hai.
     */
    'tenant_model' => Project::class,

    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => Domain::class,

    /**
     * The list of domains hosting your central app.
     * Central domains par Super Admin login aur landing page chalenge.
     */
    'central_domains' => [
        '127.0.0.1',
        'localhost',
        // Production Domain Shamil (Central App is par chalega)
        'cip-tools.de',
        'www.cip-tools.de', // Add www subdomain as well
    ],

    /**
     * Tenancy bootstrappers are executed when tenancy is initialized.
     */
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class, // Uncomment if using Redis
    ],

    /**
     * Database tenancy config.
     */
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'mysql'),

        'tenant_connection' => env('DB_CONNECTION', 'mysql'),

        'template_connection' => null,

        'database_managers' => [
            'mysql' => Stancl\Tenancy\Database\MySQLDatabaseManager::class,
            'sqlite' => Stancl\Tenancy\Database\SQLiteDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\Database\PostgreSQLDatabaseManager::class,
        ],

        'prefix' => 'tenant',
        'suffix' => '',

        'create_database_on_tenant_creation' => true, // Important: Allow auto DB creation
    ],

    /**
     * Filesystem tenancy config. Har project ki files alag folder mein store hongi.
     */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
            // 's3', // Uncomment if using S3
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => true,
    ],

    /**
     * Tenancy middleware identification.
     */
    'middleware' => [
        // Initialize tenancy by domain
        Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,

        // Prevent access to central domains by tenant
        Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
    ],

    /**
     * Features configuration
     */
    'features' => [
        Stancl\Tenancy\Features\UniversalRoutes::class,
        Stancl\Tenancy\Features\TenantConfig::class,
        Stancl\Tenancy\Features\CrossDomainRedirect::class,
    ],

    /**
     * Migration parameters
     */
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    /**
     * Tenant routes configuration
     */
    'routes' => [
        'prefix' => '/{tenant}',
    ],

    /**
     * Cache configuration for tenancy
     */
    'cache' => [
        'tag_base' => 'tenant',
        'prefix_base' => 'tenant_',
    ],

    /**
     * Redis tenancy configuration
     */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [
            'default',
        ],
    ],

    /**
     * Queue tenancy configuration
     */
    'queue' => [
        'prefix_base' => 'tenant',
    ],

    /**
     * Automatic tenant creation and deletion
     */
    'tenant_creation' => [
        'default_database' => env('DB_CONNECTION', 'mysql'),
    ],

    /**
     * Tenant deletion
     */
    'tenant_deletion' => [
        'delete_database' => true,
    ],
];
