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
        \App\Console\Commands\SynchronizeCoAuthorNetwork::class,
        \App\Console\Commands\DatabaseForeignKeyCheck::class,
        \App\Console\Commands\SynchronizeCandidate::class,
        \App\Console\Commands\CreateFullTextIndexOnAuthor::class,
        \App\Console\Commands\CreateFullTextIndexOnPaper::class,
    ];

    /**
     * Define the application's command schedule. To run use 'schedule:run'.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('co-author:sync')
            ->yearly();
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
