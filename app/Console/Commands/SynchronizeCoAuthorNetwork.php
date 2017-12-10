<?php

namespace App\Console\Commands;

use Log;
use Cache;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SynchronizeCoAuthorNetwork extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'co-author:sync {--offset=0} {--begin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize co-author network by refresh co-author
    table in database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
//        dd();
        Log::info('Sync coauthor process '.getmypid());


        // Add job info to databases
        try {
            DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'sync_coauthor')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
            //if ($this->option('begin')) {
            //    Cache::flush();
            //    $this->info('cache cleared');
            //    Cache::put('status', [], 1440);
            //}
            //
            //$offset = $this->option('offset');
            //$status = Cache::get('status');
            //$status[$offset] = false;
            //Cache::put('status', $status, 1440);

            //if (! Cache::has('authors')) {
                $authorPapers = DB::select('SELECT * FROM `author_paper`');

                $authors = [];
                $papers = [];
                $records = [];


            foreach ($authorPapers as $authorPaper) {
                    if (isset($authors[$authorPaper->author_id])) {
                        array_push($authors[$authorPaper->author_id], $authorPaper->paper_id);
                    } else {
                        $authors[$authorPaper->author_id] = [$authorPaper->paper_id];
                    }

                    if (isset($papers[$authorPaper->paper_id])) {
                        array_push($papers[$authorPaper->paper_id], $authorPaper->author_id);
                    } else {
                        $papers[$authorPaper->paper_id] = [$authorPaper->author_id];
                    }
                }

                //unset($authorPapers);

                //Cache::put('authors', $authors, 1440);
                //Cache::put('papers', $papers, 1440);
                //Cache::put('records', [], 1440);

                //$this->info(count($authors));
                //$this->info(count($papers));

                //unset($authors, $papers);

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::statement('TRUNCATE TABLE `co_authors`');
                DB::statement('TRUNCATE TABLE `candidates`');
                DB::statement('SET GLOBAL max_allowed_packet=524288000;');

                $this->info('Disable foreign key check and truncate co_authors table success');
            //}

            //$authors = Cache::get('authors');
            //$papers = Cache::get('papers');
            //$records = Cache::get('records');
    //        Cache::forget('authors');
    //        Cache::forget('papers');
    //        Cache::forget('records');

            $start = microtime(true);

            //foreach (array_slice(array_keys($authors), $offset, 10000) as $authorId) {
            foreach (array_keys($authors) as $authorId) {
    //        foreach (array_keys($authors) as $authorId) {
                $paperIds = $authors[$authorId]; // papers that author wrote

                if (count($paperIds) == 0) {
                    continue;
                }

                $collaborators = [];

                foreach ($paperIds as $pid) {
                    $collaborators = array_merge($collaborators, $papers[$pid]);
                }
                $collaborators = array_unique($collaborators);
                unset($collaborators[0]);

                // dump($authorId);
                // dump($collaborators);
                foreach ($collaborators as $collaboratorId) {
                    if ($collaboratorId != $authorId) {
                        if (isset($records[$authorId . ',' . $collaboratorId])) {
                            continue;
                        } else if (isset($records[$collaboratorId . ',' . $authorId])) {
                            continue;
                        }

                        $collaboratorPaperIds = $authors[$collaboratorId];
                        $noOfJointPapers = count(array_intersect($paperIds, $collaboratorPaperIds));

                        $collaboratorCollaborators = [];

                        foreach ($collaboratorPaperIds as $coPId) {
                            $collaboratorCollaborators = array_merge($collaboratorCollaborators, $papers[$coPId]);
                        }

                        $noOfMutualAuthors = count(array_intersect($collaborators, $collaboratorCollaborators));

                        $records[$authorId . ',' . $collaboratorId] = [$noOfJointPapers, $noOfMutualAuthors];
                    }
                }
            }

            // Cache::put('authors', $authors, 1440);
            // Cache::put('papers', $papers, 1440);
            //Cache::put('records', $records, 1440);
    //        Cache::put('candidates', [], 1440);

            //unset($authors, $papers, $records);

            //$status[$offset] = true;
            //Cache::put('status', $status, 1440);

            $this->info('Process time: ' . (string) (microtime(true) - $start));

            // check if all processes completed
    //        if (!in_array(false, array_values(TemporaryVariables::$status))) {
    //        if (! in_array(false, array_values(Cache::get('status')))) {
                $insertTime = microtime(true);
                //$records = Cache::get('records');

                if (count($records) > 0) {
                    // save records to DB
                    $result = implode(',', array_map(function ($k) use ($records) {
                        return '(\'' . $k . '\',' . $k . ',' . implode(',', $records[$k]) . ')';
                    }, array_keys($records)));


                    DB::statement('DELETE FROM `co_authors` where 1;');

                    $result = 'INSERT INTO `co_authors` (`id`, `first_author_id`, `second_author_id`, `no_of_mutual_authors`, `no_of_joint_papers`) VALUES ' . $result . ';';

                    DB::statement($result);
        //            unset all variables in Tempo
        //            DB::statement('SET GLOBAL max_allowed_packet=1048576;');

                    $this->info('Insert time: ' . (string) (microtime(true) - $insertTime));
                    Log::info('Insert time: ' . (string) (microtime(true) - $insertTime));
                }
                
            //}

            $this->info('DONE');
            Log::info('Sync coauthor done');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }

        // Remove job info from databases
        try {
            DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='sync_coauthor'");
            //$c = count(DB::select("SELECT * FROM importjobs WHERE type='sync_coauthor'"));

            //if ($c == 0) {
            //    // All job done
            //    Cache::pull('status');
            //    Cache::pull('papers');
            //    Cache::pull('authors');
            //    Cache::pull('records');
            //}
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
