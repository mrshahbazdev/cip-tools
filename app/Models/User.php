<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable; // Cashier ke liye zaroori
use Stancl\Tenancy\Facades\Tenancy; // Tenancy check ke liye

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable; 

    // ... (baqi properties jaisa ke fillable, hidden, casts)

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin', // <--- Role field shamil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean', // <--- FIX: New role casted as boolean
    ];

    /**
     * Project relationship (User is the Super Admin for many Projects)
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'super_admin_id');
    }
    
    /**
     * Filament Panel Access check (For Central Admin Dashboard)
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // 1. Central Admin Panel Protection
        if ($panel->getId() === 'admin') {
            return $this->is_super_admin;
        }

        // 2. Tenant Panel Protection (Crucial Fix)
        // Agar yeh Tenant ki request hai (aur tenant initialize ho chuka hai)
        if (Tenancy::initialized()) {
            $currentTenant = tenant();
            
            // Check if the user is the Super Admin (owner) of the current tenant
            if ($this->id !== $currentTenant->super_admin_id) {
                // Agar user owner nahi hai, to access deny karein
                return false; 
            }
        }
        
        // Agar user Central Super Admin hai ya current Tenant ka owner hai, to access grant karein.
        return true; 
    }
}