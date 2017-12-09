<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ImportAuthor_Paper;
use Exception;
use Log;
use Cache;

class ImportAuthorPaper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:author_paper {--limit=300} {--offset=0}';

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

        $author_paper_lines = array_slice(Cache::get('author_paper_lines'), $offset, $limit);

        try {
            foreach ($author_paper_lines as $key => $value) {

                if(!empty($value['authorid']))
                {
                    if(ImportAuthor_Paper::check_paper_exists($value['paperid']) && ImportAuthor_Paper::check_author_exitst($value['authorid']))
                        {
                            ImportAuthor_Paper::insert_link($value['authorid'], $value['paperid']);
                        }
                }
                
            }
        } catch (Exception $e) {
            Log::info('Author paper-----------'.$e->getMessage());
        }
    }
}
