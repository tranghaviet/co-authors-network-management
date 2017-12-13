<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use Illuminate\Support\Facades\DB;
use Log;
use Exception;
use DB;

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
        // Add job info to databases
        try {
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'paper_index')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
            $query2 = 'SET GLOBAL innodb_optimize_fulltext_only=1;';
            $query1 = 'ALTER TABLE `papers` DROP INDEX  `paper_search`;';
            $query3 = 'ALTER TABLE `papers` ADD FULLTEXT `paper_search` (`title`);';

            try {
                DB::statement($query1);
            } catch (\Exception $e) {
                \Log::info('Drop index error');
            }

            DB::statement($query2);
            DB::statement($query3);
            $this->info('Success');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        // Remove job info from databases
        try {
            \DB::statement('DELETE FROM importjobs WHERE pid = '.getmypid()." AND type='paper_index'");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
