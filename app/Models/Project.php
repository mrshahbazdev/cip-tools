<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * This is required by Stancl Tenancy v3.
     */
    public function run(callable $callback)
    {
        return tenancy()->initialize($this) ?: $callback();
    }

    /**
     * Super Admin ka relationship jo is project ko manage karta hai.
     */
    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }
}
