<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use Illuminate\Support\Facades\DB;
use Log;
use Exception;
use DB;

class CreateFullTextIndexOnAuthor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author:re-index {--university}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-index given_name, surname on author table and email on university table.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        // Add job info to databases
        try {
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'author_index')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }


        try {
            //        $query1 = 'SET GLOBAL innodb_optimize_fulltext_only=1;';
            $query2 = 'DROP INDEX IF EXISTS `authors_ft_name` ON authors;';
    //        $query3 = 'DROP INDEX IF EXISTS `universities_ft_name` ON universities;';
            $query3 = 'DROP INDEX IF EXISTS `universities_ft_name` ON universities;';
            $query4 = 'CREATE FULLTEXT INDEX authors_ft_name ON `authors` (`surname`, `given_name`);';
    //        $query5 = 'CREATE FULLTEXT INDEX universities_ft_name ON `universities` (`name`);';
            $query5 = 'ALTER TABLE `universities` ADD FULLTEXT `universities_ft_name` (`name`);';

    //        DB::statement($query1);
            DB::statement($query2);
            DB::statement($query4);

            if ($this->option('university')) {
                DB::statement($query3);
                DB::statement($query5);
            }

            $this->info('Success');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }


        // Remove job info from databases
        try {
            \DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='author_index'");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
