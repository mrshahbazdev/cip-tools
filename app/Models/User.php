<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable; // Cashier ke liye zaroori

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable; 

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
        // Central Admin Panel ko sirf wahi users access kar saken jin ka role is_super_admin hai.
        // Tenant Panel (subdomain par) ko koi bhi authenticated user access kar sakta hai.
        return $this->is_super_admin;
    }
}