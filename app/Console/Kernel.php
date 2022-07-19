<?php

namespace App\Console;

use Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule) {
        if (Config::get('mundialis.settings.enable_backups')) {
            $schedule->command('backup:clean')
            ->daily()->at('01:30');
            $schedule->command('backup:run')
                ->daily()->at('01:00');
            $schedule->command('backup:monitor')
                ->daily()->at('01:40');
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands() {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
