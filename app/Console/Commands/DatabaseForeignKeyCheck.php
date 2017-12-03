<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseForeignKeyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:foreign-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable or enable foreign key check.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->confirm('Do you want to disable foreign key check in database?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->info('Disable foreign key check successfully');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('Enable foreign key check successfully');
    }
}
