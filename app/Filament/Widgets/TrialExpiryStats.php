<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Project;
use Carbon\Carbon;

class TrialExpiryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $weekLater = $today->copy()->addWeek();
        $monthLater = $today->copy()->addMonth();

        // Data fetch karen
        $totalProjects = Project::count();
        $activeTrials = Project::where('is_active', false)->count();
        $paidProjects = Project::where('is_active', true)->count();
        $expiringThisWeek = Project::where('is_active', false)
            ->whereBetween('trial_ends_at', [$today, $weekLater])
            ->count();
        $expiringThisMonth = Project::where('is_active', false)
            ->whereBetween('trial_ends_at', [$today, $monthLater])
            ->count();
        $bonusEnabled = Project::where('pays_bonus', true)->count();

        return [
            Stat::make('Total Projects', $totalProjects)
                ->description('All registered projects')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('gray')
                ->chart($this->getProjectGrowthChart()),

            Stat::make('Active Trials', $activeTrials)
                ->description('Projects in trial period')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart($this->getTrialChartData()),

            Stat::make('Paid Projects', $paidProjects)
                ->description('Activated subscriptions')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('success')
                ->chart($this->getPaidChartData()),

            Stat::make('Expiring This Week', $expiringThisWeek)
                ->description('Trials ending in 7 days')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->chart($this->getWeeklyExpiryChart()),

            Stat::make('Expiring This Month', $expiringThisMonth)
                ->description('Trials ending in 30 days')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('orange')
                ->chart($this->getMonthlyExpiryChart()),

            Stat::make('Bonus Enabled', $bonusEnabled)
                ->description('Projects with bonus system')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('info')
                ->chart($this->getBonusChartData()),
        ];
    }

    protected function getProjectGrowthChart(): array
    {
        // Last 7 days ke new projects ka chart data
        return Project::where('created_at', '>=', Carbon::today()->subDays(7))
            ->get()
            ->groupBy(function ($project) {
                return $project->created_at->format('M j');
            })
            ->map->count()
            ->values()
            ->toArray();
    }

    protected function getTrialChartData(): array
    {
        // Active trials trend (sample data)
        return [2, 3, 5, 4, 6, 8, 7];
    }

    protected function getPaidChartData(): array
    {
        // Paid projects trend (sample data)
        return [1, 2, 1, 3, 2, 4, 3];
    }

    protected function getWeeklyExpiryChart(): array
    {
        // Next 7 days ke expiring trials (sample data)
        return [0, 1, 0, 2, 1, 0, 1];
    }

    protected function getMonthlyExpiryChart(): array
    {
        // Next 30 days ke expiring trials (sample data)
        return [1, 0, 2, 1, 0, 1, 2, 0, 1, 0, 2, 1, 0, 1, 2];
    }

    protected function getBonusChartData(): array
    {
        // Bonus enabled projects trend (sample data)
        return [1, 1, 2, 2, 3, 3, 4];
    }
}