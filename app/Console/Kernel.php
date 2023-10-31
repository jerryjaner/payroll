<?php

namespace App\Console;

use Carbon\Carbon;
use App\Jobs\MarkAbsentJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       
       // $schedule->job(new MarkAbsentJob)->dailyAt('23:59')->withoutOverlapping();
        $schedule->job(new MarkAbsentJob)->everyMinute()->withoutOverlapping();
        // $schedule->job(new MarkAbsentJob)
        //          ->dailyAt(Carbon::createFromTime(23, 59, 0, 'GMT+8')->format('H:i:s'));
      
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
