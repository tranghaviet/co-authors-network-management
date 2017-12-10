<?php

namespace App\Console\Commands;

use Log;
use Cache;
use Exception;
use App\Models\Author;
use App\Models\CoAuthor;
use App\Models\CoAuthorPaper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\TemporaryVariables;

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
//        $authors = &TemporaryVariables::$authors;
//        $papers = &TemporaryVariables::$papers;
//        $records = &TemporaryVariables::$records;
//        $candidates = &TemporaryVariables::$candidates;
//        $status = &TemporaryVariables::$status;

//        if (! Cache::has('authors')) {
//            dump('cache has gone.');
//            Cache::put('authors', 232, 56);
//        } else {
//            $this->ask('cache ok.');
//        }

//        dd();
        \Log::info('Sync coauthor process '.getmypid());


        // Add job info to databases
        try {
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'sync_coauthor')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
            if ($this->option('begin')) {
                Cache::flush();
                $this->info('cache cleared');
                Cache::put('status', [], 1440);
            }

            $offset = $this->option('offset');

            $status = Cache::get('status');
            $status[$offset] = false;
            Cache::put('status', $status, 1440);

    //        if (!isset(TemporaryVariables::$authors)) {
            if (! Cache::has('authors')) {
                $authorPapers = DB::select('SELECT * FROM `author_paper`');

                $authors = [];
                $papers = [];

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

                unset($authorPapers);

                Cache::put('authors', $authors, 1440);
                Cache::put('papers', $papers, 1440);
                Cache::put('records', [], 1440);
    //            Cache::put('candidates', [], 1440);

                $this->info(count($authors));
                $this->info(count($papers));

                unset($authors, $papers);

    //            $this->confirm('Continue?');

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::statement('TRUNCATE TABLE `co_authors`');
                DB::statement('TRUNCATE TABLE `candidates`');
                DB::statement('SET GLOBAL max_allowed_packet=524288000;');

                $this->info('Disable foreign key check and truncate co_authors table success');
            }

            $authors = Cache::get('authors');
            $papers = Cache::get('papers');
            $records = Cache::get('records');
    //        $candidates = Cache::get('candidates');
    //        Cache::forget('authors');
    //        Cache::forget('papers');
    //        Cache::forget('records');

            $start = microtime(true);

    //        foreach (array_slice(array_keys($authors), $offset, 105558 - $offset > 5000 ? 5000 : 100558 - $offset) as $authorId) {
            foreach (array_slice(array_keys($authors), $offset, 10000) as $authorId) {
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
            Cache::put('records', $records, 1440);
    //        Cache::put('candidates', [], 1440);

            unset($authors, $papers, $records);

            $status[$offset] = true;
            Cache::put('status', $status, 1440);

            $this->info('Process time: ' . (string) (microtime(true) - $start));

            // check if all processes completed
    //        if (!in_array(false, array_values(TemporaryVariables::$status))) {
            if (! in_array(false, array_values(Cache::get('status')))) {
                $insertTime = microtime(true);
                $records = Cache::get('records');

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
                }
                
            }

    //
    ////        Author::chunk(100, function ($authors) {
    //        for ($i = 0; $i < 20; $i++) {
    //            $authors = Author::orderBy('id')->offset(5 + 3 * $i)->limit(3)->get(['id']);
    //
    //            foreach ($authors as $author) {
    ////                $this->line("-----------------------------\nAuthor id: " . $author->id);
    //
    //                $papers = $author->papers()->get(['id']); // papers that author wrote
    //
    //                if (count($papers) == 0) {
    //                    continue;
    //                }
    //
    ////                    $subjects = $author->subjects()->get(['id']); // subject that author research
    ////                    $keywords = Author::keywords($author, ['id'], $papers); // all keywords in papers that author wrote
    //                $collaborators = $author->collaborators($papers, ['id']); // all author has any joint paper with $author
    //
    //                // $this->line("AUTHOR's PAPERS: " . count($papers) . ' -->' . json_encode($papers->pluck('id')->toArray()));
    //                // $this->line("AUTHOR's SUBJECTS: " . count($subjects) . ' -->' . json_encode($subjects->pluck('id')->toArray()));
    //                // $this->line("AUTHOR's KEYWORDS: " . count($keywords) . ' -->' . json_encode($keywords->pluck('id')->toArray()));
    //                // $this->line("AUTHOR's COLLABORATORS: " . count($collaborators) . ' -->' . json_encode($collaborators->pluck('id')->toArray()));
    //
    //                foreach ($collaborators as $collaborator) {
    //                    try {
    //                        // $this->line('+++++++ COLLABORATOR: ' . $collaborator->id);
    //
    ////                            DB::enableQueryLog();
    //                        // check if record already exist
    //                        // TODO: why for each $author this query always return a record except first collaborator?
    //                        $coAuthorRecord = CoAuthor::where([
    //                            'first_author_id' => $author->id,
    //                            'second_author_id' => $collaborator->id,
    //                        ])
    //                            ->orWhere([
    //                                'first_author_id' => $collaborator->id,
    //                                'second_author_id' => $author->id,
    //                            ])->first(['id']);
    //
    ////                            $laQuery = DB::getQueryLog();
    ////                            $this->line('QUERY: ' . json_encode($laQuery));
    ////                            $lcWhatYouWant = $laQuery[0]['query'] . '; BINDINGS: ' . implode(', ', $laQuery[0]['bindings']);
    ////                            $this->line($lcWhatYouWant);
    ////                            DB::disableQueryLog();
    //
    //                        if (!is_null($coAuthorRecord)) {
    ////                                $this->line('RECORD EXISTED: ' . json_encode($coAuthorRecord->attributesToArray()));
    //                            continue;
    //                        }
    //                        // compute no. of joint papers
    //                        $collaboratorPapers = $collaborator->papers()->get(['id']);
    //                        $noOfJointPapers = $collaboratorPapers->intersect($papers)->count();
    //                        // $this->line('PAPERS: ' . count($collaboratorPapers) . ' -->' . json_encode($collaboratorPapers->pluck('id')->toArray()));
    //                        // $this->line('JOINT PAPERS: ' . $noOfJointPapers . ' -->' . json_encode($jointPapers->pluck('id')->toArray()));
    //
    //                        // compute no. of mutual authors
    ////                            $coAuthorCollaborators = Author::collaborators($collaborator, ['id'], $collaboratorPapers);
    ////                            $mutualAuthors = $coAuthorCollaborators->intersect($collaborators);
    ////                            $mutualAuthors = $coAuthorCollaborators->pluck('id')->intersect($collaborators->pluck('id'));
    //                        $noOfMutualAuthors = $collaborator->collaborators($collaboratorPapers, ['id'])->intersect($collaborators)->count();
    //                        // $this->line('COLLABORATORS: ' . count($coAuthorCollaborators) . ' -->' . json_encode($coAuthorCollaborators->pluck('id')->toArray()));
    //                        // $this->line('MUTUAL AUTHORS: ' . $noOfMutualAuthors . ' -->' . json_encode($mutualAuthors->pluck('id')->toArray()));
    //
    //                        /*
    //                        // compute no. of joint subjects
    //                        $collaboratorSubjects = $collaborator->subjects()->get(['id']);
    //                        $jointSubjects = $collaboratorSubjects->intersect($subjects);
    //                        $noOfJointSubjects = $jointSubjects->count();
    //                        // $this->line('SUBJECTS: ' . count($collaboratorSubjects) . ' -->' . json_encode($collaboratorSubjects->pluck('id')->toArray()));
    //                        // $this->line('JOINT SUBJECTS: ' . $noOfJointSubjects . ' -->' . json_encode($jointSubjects->pluck('id')->toArray()));
    //
    //                        // compute no. of joint keywords
    //                        $collaboratorKeywords = Author::keywords($collaborator, ['id'], $collaboratorPapers);
    //                        $jointKeywords = $collaboratorKeywords->intersect($keywords);
    //                        $noOfJointKeywords = $jointKeywords->count();
    //                        // $this->line('KEYWORDS: ' . count($collaboratorKeywords) . ' -->' . json_encode($collaboratorKeywords->pluck('id')->toArray()));
    //                        // $this->line('JOINT KEYWORDS: ' . $noOfJointKeywords . ' -->' . json_encode($jointKeywords->pluck('id')->toArray()));
    //                        */
    //
    //                        CoAuthor::create([
    //                            'first_author_id' => $author->id,
    //                            'second_author_id' => $collaborator->id,
    //                            'no_of_mutual_authors' => $noOfMutualAuthors,
    //                            'no_of_joint_papers' => $noOfJointPapers,
    //                        ]);
    //
    ////                            $this->info('SUCCESS: ' . json_encode($coAuthorRecord->attributesToArray()));
    //                    } catch (Exception $e) {
    //                        $this->error($e->getMessage());
    //                    }
    //                }
    //            }
    //        }
    ////        });

            $this->info('DONE');
            \Log::info('Sync coauthor done');
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
        }

        // Remove job info from databases
        try {
            \DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='sync_coauthor'");
            $c = count(\DB::select("SELECT * FROM importjobs WHERE type='sync_coauthor'"));
            if ($c == 0) {
                // All job done
                Cache::pull('status');
                Cache::pull('papers');
                Cache::pull('authors');
                Cache::pull('records');
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}
