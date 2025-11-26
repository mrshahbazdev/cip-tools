<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Project;
use Carbon\Carbon;
use Stancl\Tenancy\Facades\Tenancy; // Tenancy check ke liye

class ProjectsOverview extends BaseWidget
{
    // Filament V4 mein properties static hoti hain.
    protected static ?string $heading = 'Monetization aur Trial Ka Jaiza (Overview)';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $isTenantContext = Tenancy::initialized();

        if ($isTenantContext) {
            // ---- SUBDOMAIN CONTEXT LOGIC (Individual Project) ----
            $currentProject = tenant();
            
            // Stats ko sirf is project ke liye calculate karein
            $totalProjects = 1; 
            $activeCount = $currentProject->is_active ? 1 : 0;
            $trialExpiredCount = (!$currentProject->is_active && $currentProject->trial_ends_at && $currentProject->trial_ends_at->isPast()) ? 1 : 0;
            $urgentExpiryCount = (!$currentProject->is_active && $currentProject->trial_ends_at && $currentProject->trial_ends_at->diffInDays(Carbon::now()) <= 5) ? 1 : 0;
            
            $descriptionSuffix = " for this project";
            
        } else {
            // ---- CENTRAL CONTEXT LOGIC (All Projects) ----
            // Total data across all projects
            $totalProjects = Project::count();
            $trialExpiredCount = Project::where('trial_ends_at', '<=', Carbon::today())->where('is_active', false)->count();
            $activeCount = Project::where('is_active', true)->count();
            $urgentExpiryCount = Project::where('is_active', false)
                                        ->where('trial_ends_at', '>=', Carbon::today())
                                        ->where('trial_ends_at', '<=', Carbon::today()->addDays(5))
                                        ->count();
            $descriptionSuffix = " across all projects";
        }


        return [
            Stat::make('Total Projects (Tenants)', $totalProjects)
                ->description('Total registered companies/projects' . $descriptionSuffix)
                ->color('primary')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Active Subscriptions', $activeCount)
                ->description('Paid 12-month membership projects' . $descriptionSuffix)
                ->color('success')
                ->icon('heroicon-o-currency-euro'), 

            Stat::make('Expired/Payment Pending', $trialExpiredCount)
                ->description('Payment ya Manual follow-up ki zaroorat hai' . $descriptionSuffix)
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),
            
            Stat::make('Urgent Trial Expiry (< 5 Days)', $urgentExpiryCount)
                ->description('Trial jald khatam hone wale projects' . $descriptionSuffix)
                ->color('danger')
                ->icon('heroicon-o-bell-alert'),
        ];
    }
}