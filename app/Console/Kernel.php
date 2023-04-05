<?php

namespace App\Console;

use App\Console\Commands\CalculateDepositsCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CalculateDepositsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('calculate:deposit')
            ->dailyAt('22:00')
            ->appendOutputTo('storage/logs/deposit-' . date('Y-m-d') . '.log');
    }
}
