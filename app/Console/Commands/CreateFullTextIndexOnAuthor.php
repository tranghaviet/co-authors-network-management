<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateFullTextIndexOnAuthor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author:re-index';

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
        $query2 = 'SET GLOBAL innodb_optimize_fulltext_only=1;';
        $query1 = 'ALTER TABLE `authors` DROP INDEX IF EXISTS `author_search`;';
        $query3 = 'ALTER TABLE `authors` ADD FULLTEXT `author_search` (`given_name`, `surname`);';
//        $query3 = 'ALTER TABLE `authors` ADD FULLTEXT `author_search` (`surname`);';
        $query4 = 'ALTER TABLE `universities` DROP INDEX IF EXISTS `university_search`;';
        $query5 = 'ALTER TABLE `universities` ADD FULLTEXT `university_search` (`name`);';

        DB::statement($query1);
        DB::statement($query2);
        DB::statement($query3);
        DB::statement($query4);
        DB::statement($query5);
        $this->info('Success');
    }
}
