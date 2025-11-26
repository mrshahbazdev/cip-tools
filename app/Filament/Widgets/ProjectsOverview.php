<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Project;
use Carbon\Carbon;

class ProjectsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProjects = Project::count();

        $trialExpiredCount = Project::where('trial_ends_at', '<=', Carbon::today())
                                    ->where('is_active', false)
                                    ->count();
        
        $activeCount = Project::where('is_active', true)->count();

        $urgentExpiryCount = Project::where('is_active', false)
                                    ->where('trial_ends_at', '>=', Carbon::today())
                                    ->where('trial_ends_at', '<=', Carbon::today()->addDays(5))
                                    ->count();

        return [
            Stat::make('Total Projects', $totalProjects)
                ->description('All registered companies')
                ->color('primary')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Active Subscriptions', $activeCount)
                ->description('Paid 12-month membership')
                ->color('success')
                ->icon('heroicon-o-credit-card'), 

            Stat::make('Payment Pending', $trialExpiredCount)
                ->description('Trial expired, payment required')
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),
            
            Stat::make('Urgent Expiry', $urgentExpiryCount)
                ->description('Trials expiring in less than 5 days')
                ->color('danger')
                ->icon('heroicon-o-clock'),
        ];
    }
}