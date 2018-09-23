<?php

namespace App\Console;

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
//        $schedule->call(function () {
//            DB::table('recent_users')->delete();
//        })->daily();
        
        $schedule->command('update_ratings')->withoutOverlapping(5)->daily();
        
        $usersToSync = array(
            526994012, //Francesile
            522720889, //MightyWizard83
            500957006 //Guaro90
            );
        foreach ($usersToSync as $account_id) {
            $schedule->command('WgApi:SyncPlayer '.$account_id)->withoutOverlapping(5)->everyMinute();
        }
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
