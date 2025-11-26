<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy; // Tenancy check ke liye

class TrialExpiryChart extends ChartWidget
{
    // Filament V4 mein properties static hoti hain.
    protected static ?string $heading = 'Trial Expiry Timeline (Next 30 Days)';
    protected static ?int $sort = 2; 
    protected static ?string $maxHeight = '300px'; 
    
    protected function getType(): string
    {
        return 'bar'; 
    }

    protected function getData(): array
    {
        $isTenantContext = Tenancy::initialized();
        $today = Carbon::today();
        $labels = [];
        $data = [];

        // Base Query define karein
        $query = Project::query();

        // ---- FIX: Conditional Query Scope ----
        if ($isTenantContext) {
            // Agar Subdomain par hain, to sirf current project ke data ko count karein
            $currentProject = tenant();
            // Chart sirf tab meaningful hai jab trial active ho
            if ($currentProject->is_active || $currentProject->trial_ends_at->isPast()) {
                 // Trial khatam hone ke baad chart ki zarurat nahi, sirf 0 dikhayen
                 $expiryCounts = collect();
            } else {
                 // Single project ke liye prediction data
                 $expiryCounts = Project::where('id', $currentProject->id)
                    ->select(DB::raw('DATE(trial_ends_at) as expiry_date'), DB::raw('COUNT(*) as count'))
                    ->groupBy('expiry_date')
                    ->get()
                    ->keyBy('expiry_date');
            }
        } else {
            // Central Admin Context: 30 din mein khatam hone wale tamam inactive projects
            $expiryCounts = $query
                ->select(DB::raw('DATE(trial_ends_at) as expiry_date'), DB::raw('COUNT(*) as count'))
                ->where('is_active', false)
                ->whereBetween('trial_ends_at', [$today, $today->copy()->addDays(30)])
                ->groupBy('expiry_date')
                ->orderBy('expiry_date')
                ->get()
                ->keyBy('expiry_date');
        }


        // Labels aur data points populate karein for 30 days
        for ($i = 0; $i <= 30; $i++) {
            $date = $today->copy()->addDays($i);
            $dateString = $date->toDateString();

            // Label: Jan 1, Feb 5, etc.
            $labels[] = $date->format('M j'); 
            
            // Data point: agar expiry count ho to value, warna 0
            $data[] = $expiryCounts->has($dateString) ? $expiryCounts[$dateString]->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Projects Expiring (Trial)',
                    'data' => $data,
                    'backgroundColor' => '#2563eb', // Indigo/Blue shade
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}