<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Author;
use App\Models\CoAuthor;
use App\Models\CoAuthorPaper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SynchronizeCoAuthorNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'co-author:sync';

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
        // @TODO Test
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE `co_authors`');
        DB::statement('TRUNCATE TABLE `candidates`');
        $this->info('Disable foreign key check and truncate co_authors table success');

//        $offset = floatval($this->option('offset'));

        Author::chunk(100, function ($authors) {
//            $authors = Author::orderBy('id')->offset($offset + 250 * $i)->limit(250)->get(['id']);

            foreach ($authors as $author) {
//                $this->line("-----------------------------\nAuthor id: " . $author->id);

                $papers = $author->papers()->get(['id']); // papers that author wrote

                if (count($papers) == 0) {
                    continue;
                }

//                    $subjects = $author->subjects()->get(['id']); // subject that author research
//                    $keywords = Author::keywords($author, ['id'], $papers); // all keywords in papers that author wrote
                $collaborators = $author->collaborators($papers, ['id']); // all author has any joint paper with $author

                // $this->line("AUTHOR's PAPERS: " . count($papers) . ' -->' . json_encode($papers->pluck('id')->toArray()));
                // $this->line("AUTHOR's SUBJECTS: " . count($subjects) . ' -->' . json_encode($subjects->pluck('id')->toArray()));
                // $this->line("AUTHOR's KEYWORDS: " . count($keywords) . ' -->' . json_encode($keywords->pluck('id')->toArray()));
                // $this->line("AUTHOR's COLLABORATORS: " . count($collaborators) . ' -->' . json_encode($collaborators->pluck('id')->toArray()));

                foreach ($collaborators as $collaborator) {
                    try {
                        // $this->line('+++++++ COLLABORATOR: ' . $collaborator->id);

//                            DB::enableQueryLog();
                        // check if record already exist
                        // TODO: why for each $author this query always return a record except first collaborator?
                        $coAuthorRecord = CoAuthor::where([
                            'first_author_id' => $author->id,
                            'second_author_id' => $collaborator->id,
                        ])
                            ->orWhere([
                                'first_author_id' => $collaborator->id,
                                'second_author_id' => $author->id,
                            ])->first(['id']);

//                            $laQuery = DB::getQueryLog();
//                            $this->line('QUERY: ' . json_encode($laQuery));
//                            $lcWhatYouWant = $laQuery[0]['query'] . '; BINDINGS: ' . implode(', ', $laQuery[0]['bindings']);
//                            $this->line($lcWhatYouWant);
//                            DB::disableQueryLog();

                        if (!is_null($coAuthorRecord)) {
//                                $this->line('RECORD EXISTED: ' . json_encode($coAuthorRecord->attributesToArray()));
                            continue;
                        }
                        // compute no. of joint papers
                        $collaboratorPapers = $collaborator->papers()->get(['id']);
                        $noOfJointPapers = $collaboratorPapers->intersect($papers)->count();
                        // $this->line('PAPERS: ' . count($collaboratorPapers) . ' -->' . json_encode($collaboratorPapers->pluck('id')->toArray()));
                        // $this->line('JOINT PAPERS: ' . $noOfJointPapers . ' -->' . json_encode($jointPapers->pluck('id')->toArray()));

                        // compute no. of mutual authors
//                            $coAuthorCollaborators = Author::collaborators($collaborator, ['id'], $collaboratorPapers);
//                            $mutualAuthors = $coAuthorCollaborators->intersect($collaborators);
//                            $mutualAuthors = $coAuthorCollaborators->pluck('id')->intersect($collaborators->pluck('id'));
                        $noOfMutualAuthors = $collaborator->collaborators($collaboratorPapers, ['id'])->intersect($collaborators)->count();
                        // $this->line('COLLABORATORS: ' . count($coAuthorCollaborators) . ' -->' . json_encode($coAuthorCollaborators->pluck('id')->toArray()));
                        // $this->line('MUTUAL AUTHORS: ' . $noOfMutualAuthors . ' -->' . json_encode($mutualAuthors->pluck('id')->toArray()));

                        /*
                        // compute no. of joint subjects
                        $collaboratorSubjects = $collaborator->subjects()->get(['id']);
                        $jointSubjects = $collaboratorSubjects->intersect($subjects);
                        $noOfJointSubjects = $jointSubjects->count();
                        // $this->line('SUBJECTS: ' . count($collaboratorSubjects) . ' -->' . json_encode($collaboratorSubjects->pluck('id')->toArray()));
                        // $this->line('JOINT SUBJECTS: ' . $noOfJointSubjects . ' -->' . json_encode($jointSubjects->pluck('id')->toArray()));

                        // compute no. of joint keywords
                        $collaboratorKeywords = Author::keywords($collaborator, ['id'], $collaboratorPapers);
                        $jointKeywords = $collaboratorKeywords->intersect($keywords);
                        $noOfJointKeywords = $jointKeywords->count();
                        // $this->line('KEYWORDS: ' . count($collaboratorKeywords) . ' -->' . json_encode($collaboratorKeywords->pluck('id')->toArray()));
                        // $this->line('JOINT KEYWORDS: ' . $noOfJointKeywords . ' -->' . json_encode($jointKeywords->pluck('id')->toArray()));
                        */

                        CoAuthor::create([
                            'first_author_id' => $author->id,
                            'second_author_id' => $collaborator->id,
                            'no_of_mutual_authors' => $noOfMutualAuthors,
                            'no_of_joint_papers' => $noOfJointPapers,
                        ]);

//                            $this->info('SUCCESS: ' . json_encode($coAuthorRecord->attributesToArray()));

//                            unset($coAuthorRecord, $collaborator->id,
//                                $coAuthorCollaborators, $mutualAuthors, $noOfMutualAuthors,
//                                $collaboratorPapers, $jointPapers, $noOfJointPapers,
//                                $collaboratorSubjects, $jointSubjects, $noOfJointSubjects,
//                                $collaboratorKeywords, $jointKeywords, $noOfJointKeywords);
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                }
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('DONE');
    }
}
