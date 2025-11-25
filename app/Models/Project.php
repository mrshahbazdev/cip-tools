<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDomains; // Zaroori Interface
use Stancl\Tenancy\Database\Concerns\HasDomains; // Zaroori Trait
use Illuminate\Database\Eloquent\Factories\HasFactory; // Filament aur testing ke liye

class Project extends Model implements TenantWithDomains // <--- Interface implement karein
{
    use HasFactory, HasDomains; // <--- Sirf HasDomains rakhen, IsTenant hata diya

    // Ye fields mass assignment ke liye allow ki gayi hain
    protected $fillable = [
        'name',
        'subdomain',
        'super_admin_id',
        'pays_bonus',
        'trial_ends_at',
        'is_active',
    ];

    /**
     * Super Admin ka relationship jo is project ko manage karta hai.
     */
    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }
}
