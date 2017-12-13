<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ImportPaper;
use Exception;
use Log;
use Cache;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Artisan;

class ImportPapers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:papers {--offset=0} {--limit=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $UNKNOWN='UNKNOWN';
        $offset = $this->option('offset');
        $limit = $this->option('limit');

        // Add job info to databases
        try {
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'paper')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        $paper_lines = array_slice(Cache::get('paper_lines'), $offset, $limit);

        try {
            foreach ($paper_lines as $key => $value) {

                if(!empty($value['id'])){

                    // Import papers
                    ImportPaper::insert_papers($value['id'], $value['title'], $value['coverdate'] ,
                        $value['abstract'], $value['url'], $value['issn']);
                    
                    // Tách nhóm từ khóa thành nhiều từ khóa 
                    $keywords = preg_split('/,\s*/', trim($value['keywords']), -1, PREG_SPLIT_NO_EMPTY);
                    
                    // với mỗi từ khóa thêm vào cùng với bài báo: keyword-paper
                    foreach ($keywords as $keyword)
                    {
                        ImportPaper::handle_keywords($value['id'], $keyword);
                    }
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        // Remove job info from databases
        try {
            \DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='paper'");
            $c = count(\DB::select("SELECT * FROM importjobs WHERE type='paper'"));
            if ($c == 0) {
                // All job done
                Cache::pull('paper_lines');
            }
            
            // Artisan::call('paper:re-index');
            $process = new Process('php ../artisan paper:re-index');
            $process->start();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
        
    }
}
