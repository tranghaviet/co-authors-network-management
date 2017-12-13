<?php

namespace App\Console\Commands;

use Log;
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
        \Log::info('Sync coauthor process ' . getmypid());

        // Add job info to databases
        try {
            DB::statement('INSERT INTO importjobs VALUES (' . getmypid() . ", 'sync_coauthor')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
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

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('TRUNCATE TABLE `co_authors`');
            DB::statement('TRUNCATE TABLE `candidates`');
            DB::statement('SET GLOBAL max_allowed_packet=524288000;');

            $this->info('Disable foreign key check and truncate co_authors table success');

            $start = microtime(true);

            foreach (array_keys($authors) as $authorId) {
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

                foreach ($collaborators as $collaboratorId) {
                    if ($collaboratorId != $authorId) {
                        if (isset($records[$authorId . ',' . $collaboratorId])) {
                            continue;
                        } else {
                            if (isset($records[$collaboratorId . ',' . $authorId])) {
                                continue;
                            }
                        }

                        $collaboratorPaperIds = $authors[$collaboratorId];
                        $noOfJointPapers = count(array_intersect($paperIds, $collaboratorPaperIds));

                        $collaboratorCollaborators = [];

                        foreach ($collaboratorPaperIds as $coPId) {
                            $collaboratorCollaborators = array_merge($collaboratorCollaborators, $papers[$coPId]);
                        }

                        $noOfMutualAuthors = count(array_intersect($collaborators, $collaboratorCollaborators));

                        $records[$authorId . ',' . $collaboratorId] = [
                            $noOfJointPapers,
                            $noOfMutualAuthors,
                        ];
                    }
                }
            }

            $this->info('Process time: ' . (string) (microtime(true) - $start));

            $insertTime = microtime(true);

            if (count($records) > 0) {
                // save records to DB
                $result = implode(',', array_map(function ($k) use ($records) {
                    return '(\'' . $k . '\',' . $k . ',' . implode(',', $records[$k]) . ')';
                }, array_keys($records)));

                DB::statement('DELETE FROM `co_authors` where 1;');

                $result = 'INSERT INTO `co_authors` (`id`, `first_author_id`, `second_author_id`, `no_of_mutual_authors`, `no_of_joint_papers`) VALUES ' . $result . ';';

                DB::statement($result);

                // unset all variables in Tempo
                // DB::statement('SET GLOBAL max_allowed_packet=1048576;');\

                $this->info('Insert time: ' . (string) (microtime(true) - $insertTime));
                Log::info('Insert time: ' . (string) (microtime(true) - $insertTime));
            }

            Log::info('Sync coauthor done');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }

        // Remove job info from databases
        try {
            DB::statement('DELETE FROM importjobs WHERE pid = ' . getmypid() . " AND type='sync_coauthor'");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
