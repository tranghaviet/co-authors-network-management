<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateFullTextIndexOnPaper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paper:re-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-index title on papers table.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $query2 = 'SET GLOBAL innodb_optimize_fulltext_only=1;';
        $query1 = 'ALTER TABLE `papers` DROP INDEX IF EXISTS `paper_search`;';
        $query3 = 'ALTER TABLE `papers` ADD FULLTEXT `paper_search` (`title`);';

        DB::statement($query1);
        DB::statement($query2);
        DB::statement($query3);
        $this->info('Success');
    }
}
