<?php

namespace App\Console\Commands;

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
        If yes, you need to re-compute candidate table due to incorrect co-_author_id.');

        if ($this->isRefreshTable) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('TRUNCATE TABLE `co_authors`');
        }

        Author::chunk(200, function ($authors) {
            foreach ($authors as $author) {
                $this->info('Author id: ' . $author->id);

                $papers = $author->papers; // papers that author wrote

                if ($papers->isEmpty()) {
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

                foreach ($collaborators as $collaborator) {
                    // check if record already exist
                    $coAuthorRecord = CoAuthor::where(['first_author_id' => $collaborator->id,
                        'second_author_id' => $author->id])
                        ->orWhere(['first_author_id' => $author->id,
                            'second_author_id' => $collaborator->id])->first();
                    $this->info($coAuthorRecord->isNotEmpty());

                    if ($coAuthorRecord->isNotEmpty() && $this->isRefreshTable) {
                        continue;
                    }
                    // compute no. of mutual authors
                    $coAuthorCollaborators = $collaborator->collaborators(['id']);
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

                    if ($coAuthorRecord->isNotEmpty() && !$this->isRefreshTable) {
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

                    $this->info('Success' . json_encode($coAuthorRecord->toArray()));
                }
            }
        });

        if ($this->isRefreshTable) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
