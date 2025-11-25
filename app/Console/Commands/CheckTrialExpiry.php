<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Illuminate\Support\Carbon;

class CheckTrialExpiry extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'cip:check-trial-expiry'; // Unique command signature

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Checks for expiring trial projects and sends notifications (20, 25, 30 days).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // --- 1. 10 Din Baqi (20 din guzar chuke) ---
        $tenDaysLeft = $today->copy()->addDays(10);
        $this->notifyAdmins(
            Project::whereDate('trial_ends_at', $tenDaysLeft)->get(),
            10
        );

        // --- 2. 5 Din Baqi (25 din guzar chuke) ---
        $fiveDaysLeft = $today->copy()->addDays(5);
        $this->notifyAdmins(
            Project::whereDate('trial_ends_at', $fiveDaysLeft)->get(),
            5
        );

        // --- 3. Aaj Akhri Din Hai (30 din guzar chuke) ---
        $todayExpiry = $today->copy();
        $this->notifyAdmins(
            Project::whereDate('trial_ends_at', $todayExpiry)->get(),
            0
        );

        $this->info('Trial expiry check complete. Processed notifications.');
        return Command::SUCCESS;
    }

    protected function notifyAdmins($projects, $daysLeft)
    {
        $days = ($daysLeft === 0) ? 'LAST DAY' : "{$daysLeft} days";

        foreach ($projects as $project) {
            $admin = $project->superAdmin;

            // Console par output (For testing only)
            $this->warn("ALERT: Project {$project->name} ({$admin->email}) has {$days} remaining.");

            // TODO: Next step mein yahan Filament Notification ya Email ka code aayega.
        }
    }
}
