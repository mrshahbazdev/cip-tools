<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Project;
use Carbon\Carbon;

class ProjectsOverview extends BaseWidget
{
    protected static ?string $heading = 'Monetization aur Trial Ka Jaiza (Overview)';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Total projects
        $totalProjects = Project::count();

        // Trial khatam, payment pending (Inactive projects)
        $trialExpiredCount = Project::where('trial_ends_at', '<=', Carbon::today())
                                    ->where('is_active', false)
                                    ->count();
        
        // Active Subscriptions (Paid)
        $activeCount = Project::where('is_active', true)->count();

        // Urgent Expiry (5 din ya us se kam baqi)
        $urgentExpiryCount = Project::where('is_active', false)
                                    ->where('trial_ends_at', '>=', Carbon::today())
                                    ->where('trial_ends_at', '<=', Carbon::today()->addDays(5))
                                    ->count();

        return [
            Stat::make('Total Projects (Tenants)', $totalProjects)
                ->description('Total registered companies/projects')
                ->color('primary')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Active Subscriptions', $activeCount)
                ->description('Paid 12-month membership projects')
                ->color('success')
                ->icon('heroicon-o-currency-euro'), 

            Stat::make('Expired/Payment Pending', $trialExpiredCount)
                ->description('Payment ya Manual follow-up ki zaroorat hai')
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),
            
            Stat::make('Urgent Trial Expiry (< 5 Days)', $urgentExpiryCount)
                ->description('Trial jald khatam hone wale projects')
                ->color('danger')
                ->icon('heroicon-o-bell-alert'),
        ];
    }
}