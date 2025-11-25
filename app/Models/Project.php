<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Facades\Tenancy; // Tenancy facade use karna

class Project extends Model implements TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains;

    protected $fillable = [
        'name',
        'subdomain',
        'super_admin_id',
        'pays_bonus',
        'trial_ends_at',
        'is_active',
    ];

    // --- FIX 1: $casts property shamil karna (Date Formatting Ke Liye) ---
    protected $casts = [
        'trial_ends_at' => 'datetime', // String se Carbon object mein convert karega
        'pays_bonus' => 'boolean',
        'is_active' => 'boolean',
    ];
    // ------------------------------------------

    /**
     * Get the name of the tenant key (primary key).
     */
    public function getTenantKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the value of the tenant key (primary key).
     */
    public function getTenantKey(): string|int
    {
        return $this->getKey();
    }

    /**
     * Get the internal identifier for the tenant.
     */
    public function getInternal(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    /**
     * Set the internal identifier for the tenant.
     */
    public function setInternal(string $key, mixed $value): static
    {
        $this->setAttribute($key, $value);
        return $this;
    }

    /**
     * Get all the tenant's attributes.
     */
    public function getAttributes(): array
    {
        return parent::getAttributes();
    }

    /**
     * Run callback in tenant's context.
     * FIX 2: Correct Tenancy V3 run implementation.
     */
    public function run(callable $callback)
    {
        // Fix: run method ko Base Tenant contract ke liye theek tareeqay se implement karna
        return Tenancy::initialize($this)->run($callback);
    }

    /**
     * Super Admin ka relationship jo is project ko manage karta hai.
     */
    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }
}