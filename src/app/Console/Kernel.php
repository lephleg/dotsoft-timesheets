<?php

namespace App\Console;

use App\DailyLookup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
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
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){

            // Generate yesterday's date
            $date = new \DateTime('', new \DateTimeZone('Europe/Athens'));
            $date->sub(new \DateInterval('P1D'));
            $yesterday = $date->format('Y-m-d');

            // Trigger and log the calculation
            \Log::notice('Daily average time calculation has started. Please wait..');
            DailyLookup::store($yesterday);
            \Log::info('Daily average time calculation has been completed!');

        })->dailyAt('02:05');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
