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
            $query1 = 'DROP INDEX  `authors_fulltext_surname` ON authors;';
            $query2 = 'DROP INDEX  `authors_fulltext_given_name` ON authors;';
            $query3 = 'DROP INDEX  `universities_ft_name` ON universities;';

            // $query4 = 'ALTER TABLE `authors` ADD FULLTEXT `authors_fulltext_surname` (`surname`);';
            $query6 = 'ALTER TABLE `authors` ADD FULLTEXT `authors_fulltext_given_name` (`given_name`);';
            $query4 = 'CREATE FULLTEXT INDEX authors_fulltext_surname ON `authors` (`surname`);';
            // $query6 = 'CREATE FULLTEXT INDEX authors_fulltext_given_name ON `authors` (`given_name`);';
            $query5 = 'ALTER TABLE `universities` ADD FULLTEXT `universities_ft_name` (`name`);';

            try {
                DB::statement($query1);
                DB::statement($query2);
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }

            DB::statement($query4);
            DB::statement($query6);

            //        DB::statement($query1);

            if ($this->option('university')) {
                try {
                    DB::statement($query3);
                } catch (\Exception $e) {
                    \Log::info($e->getMessage());
                }

                DB::statement($query5);
            }

            $this->info('Success');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        // Remove job info from databases
        try {
            \DB::statement('DELETE FROM importjobs WHERE pid = '.getmypid()." AND type='author_index'");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
