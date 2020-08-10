<?php

namespace App\Console;

use App\Notifications\PayTax;
use App\User;
use Carbon\Carbon;
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
        $schedule->call(function (){
            $users = User::all();
            $current = new Carbon();
            foreach ($users as $user){
                if ($user->notification_period == 'month'
                    || ($user->notification_period == 'quarter'
                        && in_array($current->month, [4, 7, 10, 1]))){
                    $user->notify(new PayTax());
                }
            }
        })->monthlyOn(2, '15:00');;
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
