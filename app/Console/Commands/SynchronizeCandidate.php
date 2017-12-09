<?php

namespace App\Console\Commands;

use Exception;
use App\Models\CoAuthor;
use App\Models\Candidate;
use Illuminate\Console\Command;
use App\Helpers\MeasureLinking;
use App\Helpers\CoAuthorHelper;
use Illuminate\Support\Facades\DB;

class SynchronizeCandidate extends Command
{
    protected $isTruncateTable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidate:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize candidate.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->isTruncateTable = $this->confirm('Do you want to truncate table candidates?');

//        if ($this->isTruncateTable) {
        DB::statement('TRUNCATE TABLE `candidates`');

        CoAuthor::chunk(100, function ($coAuthors) {
            $candidates = [];

            foreach ($coAuthors as $coAuthor) {
                $firstCoAuthors = CoAuthorHelper::collaborators($coAuthor['first_author_id'], ['id']);
                $secondCoAuthors = CoAuthorHelper::collaborators($coAuthor['second_author_id'], ['id']);


                $wcn = MeasureLinking::wcn($firstCoAuthors, $secondCoAuthors);
                $waa = MeasureLinking::waa($firstCoAuthors, $secondCoAuthors);
//                        $wjc = MeasureLinking::wjc($firstCoAuthors, $secondCoAuthors);
//                 $wca = MeasureLinking::wca($firstCoAuthors, $secondCoAuthors);

                array_push($candidates, [
                    'co_author_id' => $coAuthor->id,
                    'score_1' => $wcn,
                    // 'score_2' => $waa,
                    // 'score_3' => $wca,
                ]);
            }
            Candidate::insert($candidates);
        });
//        }
    }
}
