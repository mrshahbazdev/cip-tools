<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDatabase; // Updated interface for v3
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model implements TenantWithDatabase // Use TenantWithDatabase for v3
{
    use HasFactory, HasDatabase, HasDomains; // Both traits are required

    protected $fillable = [
        'name',
        'subdomain',
        'super_admin_id',
        'pays_bonus',
        'trial_ends_at',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'is_active' => 'boolean',
            'pays_bonus' => 'boolean',
        ];
    }

    /**
     * Relationship with the super admin user
     */
    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }

    /**
     * Get the custom domain attribute
     */
    public function getDomainAttribute(): string
    {
        return $this->subdomain . '.' . config('tenancy.central_domains.0');
    }
}
