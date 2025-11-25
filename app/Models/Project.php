<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDomains; // Zaroori Interface
use Stancl\Tenancy\Database\Concerns\HasDomains; // Zaroori Trait
use Stancl\Tenancy\Database\Concerns\IsTenant; // Zaroori Trait

class Project extends Model implements TenantWithDomains // <--- TenantWithDomains Implement Karein
{
    // Tenancy ke liye ye dono traits zaroori hain
    use HasDomains, IsTenant;

    // Ye fields mass assignment ke liye allow ki gayi hain
    protected $fillable = [
        'name',
        'subdomain',
        'super_admin_id',
        'pays_bonus',
        'trial_ends_at',
        'is_active',
        // Aap baad mein logo, slogan aur baki fields yahan add karenge
    ];

    /**
     * Super Admin ka relationship jo is project ko manage karta hai.
     */
    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }
}
