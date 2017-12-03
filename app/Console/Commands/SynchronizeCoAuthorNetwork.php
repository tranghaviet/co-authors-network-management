<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Author;
use App\Models\CoAuthor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SynchronizeCoAuthorNetwork extends Command
{
    protected $isRefreshTable;

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
        $this->isRefreshTable = $this->confirm('Do you want to refresh entirely co_authors table?
        If yes, you need to re-compute candidate table due to incorrect co_author_id.');

        if ($this->isRefreshTable) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('TRUNCATE TABLE `co_authors`');
            $this->info('Disable foreign key check and truncate co_authors table success');
        }

        Author::chunk(200, function ($authors) {
            foreach ($authors as $author) {
                $first_author_id = $author->id;

                $this->line("-----------------------------\nAuthor id: " . $first_author_id);

                try {
                    $papers = $author->papers()->get(['id']); // papers that author wrote

                    if (count($papers) == 0) {
                        continue;
                    }

                    $subjects = $author->subjects()->get(['id']); // subject that author research
                    $keywords = collect(); // all keywords in papers that author wrote
                    $collaborators = collect(); // all author has any joint paper with $author
                    $collaboratorIds = []; // ids of all author has any joint paper with $author

                    foreach ($papers as $paper) {
                        $authorsWriteOnePaper = $paper->authors()->where('id', '!=', $first_author_id)
                            ->whereNotIn('id', $collaboratorIds)->get(['id']);

                        $collaboratorIds = array_merge($collaboratorIds, $authorsWriteOnePaper->map(function ($authorWriteOnePaper) {
                            return $authorWriteOnePaper->id;
                        })->toArray());
                        $collaborators = $collaborators->merge($authorsWriteOnePaper);

                        $keywords = $keywords->merge($paper->keywords()->get(['id']));
                    }
                    $this->line("AUTHOR's PAPERS: " . count($papers) . ' -->' . json_encode($papers->pluck('id')->toArray()));
                    $this->line("AUTHOR's SUBJECTS: " . count($subjects) . ' -->' . json_encode($subjects->pluck('id')->toArray()));
                    $this->line("AUTHOR's KEYWORDS: " . count($keywords) . ' -->' . json_encode($keywords->pluck('id')->toArray()));
                    $this->line("AUTHOR's COLLABORATORS: " . count($collaborators) . ' -->' . json_encode($collaborators->pluck('id')->toArray()));

                    foreach ($collaborators as $collaborator) {
                        try {
                            $second_author_id = $collaborator->id;

                            $this->line('+++++++ COLLABORATOR: ' . $second_author_id);

//                            DB::enableQueryLog();
                            // check if record already exist
                                // TODO: why for each $author this query always return a record except first collaborator?
//                            $coAuthorRecord = CoAuthor::where([
//                                'first_author_id' => $collaborator->id,
//                                'second_author_id' => $author->id,
//                            ])
//                                ->orWhere([
//                                    'first_author_id' => $author->id,
//                                    'second_author_id' => $collaborator->id,
//                                ])->first();
                                $coAuthorRecord = CoAuthor::where([
                                    'first_author_id' => $first_author_id,
                                'second_author_id' => $second_author_id,
                            ])
                                ->orWhere([
                                    'first_author_id' => $second_author_id,
                                    'second_author_id' => $first_author_id,
                                ])->first();

//                            $laQuery = DB::getQueryLog();
//                            $this->line('QUERY: ' . json_encode($laQuery));
//                            $lcWhatYouWant = $laQuery[0]['query'] . '; BINDINGS: ' . implode(', ', $laQuery[0]['bindings']);
//                            $this->line($lcWhatYouWant);
//                            DB::disableQueryLog();

                            if (count($coAuthorRecord) != 0 && $this->isRefreshTable) {
                                $this->line('RECORD EXISTED: ' . json_encode($coAuthorRecord->attributesToArray()));
                                continue;
                            }
                            // compute no. of joint papers
                            $collaboratorPapers = $collaborator->papers()->get(['id']);
                            $jointPapers = $collaboratorPapers->intersect($papers);
                            $noOfJointPapers = $jointPapers->count();
                            $this->line('PAPERS: ' . count($collaboratorPapers) . ' -->' . json_encode($collaboratorPapers->pluck('id')->toArray()));
                            $this->line('JOINT PAPERS: ' . $noOfJointPapers . ' -->' . json_encode($jointPapers->pluck('id')->toArray()));

                            // compute no. of mutual authors
                            $coAuthorCollaborators = Author::collaborators($collaborator, ['id'], $collaboratorPapers);
                            $mutualAuthors = $coAuthorCollaborators->intersect($collaborators);
//                            $mutualAuthors = $coAuthorCollaborators->pluck('id')->intersect($collaborators->pluck('id'));
                            $noOfMutualAuthors = $mutualAuthors->count();
                            $this->line('COLLABORATORS: ' . count($coAuthorCollaborators) . ' -->' . json_encode($coAuthorCollaborators->pluck('id')->toArray()));
                            $this->line('MUTUAL AUTHORS: ' . $noOfMutualAuthors . ' -->' . json_encode($mutualAuthors->pluck('id')->toArray()));

                            // compute no. of joint subjects
                            $collaboratorSubjects = $collaborator->subjects()->get(['id']);
                            $jointSubjects = $collaboratorSubjects->intersect($subjects);
                            $noOfJointSubjects = $jointSubjects->count();
                            $this->line('SUBJECTS: ' . count($collaboratorSubjects) . ' -->' . json_encode($collaboratorSubjects->pluck('id')->toArray()));
                            $this->line('JOINT SUBJECTS: ' . $noOfJointSubjects . ' -->' . json_encode($jointSubjects->pluck('id')->toArray()));

                            // compute no. of joint keywords
                            $collaboratorKeywords = Author::keywords($collaborator, ['id'], $collaboratorPapers);
                            $jointKeywords = $collaboratorKeywords->intersect($keywords);
                            $noOfJointKeywords = $jointKeywords->count();
                            $this->line('KEYWORDS: ' . count($collaboratorKeywords) . ' -->' . json_encode($collaboratorKeywords->pluck('id')->toArray()));
                            $this->line('JOINT KEYWORDS: ' . $noOfJointKeywords . ' -->' . json_encode($jointKeywords->pluck('id')->toArray()));

                            if (count($coAuthorRecord) != 0 && !$this->isRefreshTable) {
                                $coAuthorRecord->update([
                                    'no_of_mutual_authors' => $noOfMutualAuthors,
                                    'no_of_joint_papers' => $noOfJointPapers,
                                    'no_of_joint_subjects' => $noOfJointSubjects,
                                    'no_of_joint_keywords' => $noOfJointKeywords,
                                ]);
                            } else {
                                $coAuthorRecord = CoAuthor::create([
                                    'first_author_id' => $first_author_id,
                                    'second_author_id' => $second_author_id,
                                    'no_of_mutual_authors' => $noOfMutualAuthors,
                                    'no_of_joint_papers' => $noOfJointPapers,
                                    'no_of_joint_subjects' => $noOfJointSubjects,
                                    'no_of_joint_keywords' => $noOfJointKeywords,
                                ]);
                            }

                            $this->info('SUCCESS: ' . json_encode($coAuthorRecord->attributesToArray()));
                            unset($coAuthorRecord, $second_author_id,
                                $coAuthorCollaborators, $mutualAuthors, $noOfMutualAuthors,
                                $collaboratorPapers, $jointPapers, $noOfJointPapers,
                                $collaboratorSubjects, $jointSubjects, $noOfJointSubjects,
                                $collaboratorKeywords, $jointKeywords, $noOfJointKeywords);
                        } catch (Exception $e) {
                            $this->error($e->getMessage());
                        }
                    }
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
        });

        if ($this->isRefreshTable) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('Enable foreign key check success.');
        }
    }
}
