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
                $this->info('Author id: ' . $author->id);

                try {
                    $papers = $author->papers; // papers that author wrote

                    if (count($papers) == 0) {
                        continue;
                    }

                    $subjects = $author->subjects()->get(['id']); // subject that author research
                    $keywords = collect(); // all keywords in papers that author wrote
                    $collaborators = collect(); // all author has any joint paper with $author
                    $collaboratorIds = []; // ids of all author has any joint paper with $author

                    foreach ($papers as $paper) {
                        $authorsWriteOnePaper = $paper->authors()->where('id', '!=', $author->id)
                            ->whereNotIn('id', $collaboratorIds)->get(['id']);

                        $collaboratorIds = array_merge($collaboratorIds, $authorsWriteOnePaper->map(function ($authorWriteOnePaper) {
                            return $authorWriteOnePaper->id;
                        })->toArray());
                        $collaborators = $collaborators->merge($authorsWriteOnePaper);

                        $keywords = $keywords->merge($paper->keywords()->get(['id']));
                    }
                    $this->info('Collaborators ' . $collaborators->toJson());

                    foreach ($collaborators as $collaborator) {
                        try {
                            // check if record already exist
                            $coAuthorRecord = CoAuthor::where(['first_author_id' => $collaborator->id,
                                'second_author_id' => $author->id])
                                ->orWhere(['first_author_id' => $author->id,
                                    'second_author_id' => $collaborator->id])->first();

                            if (count($coAuthorRecord) != 0 && $this->isRefreshTable) {
                                $this->line('RECORD ' . $coAuthorRecord->id . ' EXISTED');
                                continue;
                            }
                            // compute no. of mutual authors
                            $coAuthorCollaborators = Author::collaborators($collaborator, ['id']);
                            $noOfMutualAuthors = $coAuthorCollaborators->intersect($collaborators)->count();

                            // compute no. of joint papers
                            $collaboratorPapers = $collaborator->papers()->get(['id']);
                            $noOfJointPapers = $collaboratorPapers->intersect($papers)->count();

                            // compute no. of joint subjects
                            $collaboratorSubjects = $collaborator->subjects()->get(['id']);
                            $noOfJointSubjects = $collaboratorSubjects->intersect($subjects)->count();

                            // compute no. of joint keywords
                            $collaboratorKeywords = collect();
                            foreach ($collaboratorPapers as $collaboratorPaper) {
                                $collaboratorKeywords = $collaboratorKeywords->merge($collaboratorPaper->keywords()->get(['id']));
                            }
                            $noOfJointKeywords = $collaboratorKeywords->intersect($keywords)->count();

                            if (count($coAuthorRecord) != 0 && !$this->isRefreshTable) {
                                $coAuthorRecord->update([
                                    'no_of_mutual_authors' => $noOfMutualAuthors,
                                    'no_of_joint_papers' => $noOfJointPapers,
                                    'no_of_joint_subjects' => $noOfJointSubjects,
                                    'no_of_joint_keywords' => $noOfJointKeywords,
                                ]);
                            } else {
                                $coAuthorRecord = CoAuthor::create([
                                    'first_author_id' => $author->id,
                                    'second_author_id' => $collaborator->id,
                                    'no_of_mutual_authors' => $noOfMutualAuthors,
                                    'no_of_joint_papers' => $noOfJointPapers,
                                    'no_of_joint_subjects' => $noOfJointSubjects,
                                    'no_of_joint_keywords' => $noOfJointKeywords,
                                ]);
                            }

                            $this->info('SUCCESS: ' . json_encode($coAuthorRecord->attributesToArray()));
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
