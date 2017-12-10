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
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('TRUNCATE TABLE `candidates`');
            try {
                DB::statement('ALTER TABLE candidates DROP FOREIGN KEY candidates_co_author_id_foreign;');
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
            }


            CoAuthor::chunk(500, function ($coAuthors) {
                $candidates = [];

                foreach ($coAuthors as $coAuthor) {
                    $firstCoAuthors = CoAuthorHelper::collaborators($coAuthor['first_author_id'], ['id']);
                    $secondCoAuthors = CoAuthorHelper::collaborators($coAuthor['second_author_id'], ['id']);

                    $scores = MeasureLinking::wcn_waa_wca($firstCoAuthors, $secondCoAuthors);

                    array_push($candidates, [
                        'co_author_id' => $coAuthor->id,
                        'score_1' => $scores['wcn'],
                        'score_2' => $scores['waa'],
                        'score_3' => $scores['wca'],
                    ]);
                }
                Candidate::insert($candidates);
            });
            DB::statement('ALTER TABLE `candidates` ADD constraint  candidates_co_author_id_foreign 
                FOREIGN KEY  (co_author_id) REFERENCES co_authors(id);');
        } catch (Exception $e) {
            \Log::debug($e->getMessage());
        }
        
    }
}
