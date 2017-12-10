<?php

namespace App\Console\Commands;

use Exception;
use Log;
use Cache;
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
            Cache::pull('co_authors_map');
            // Cache::flush();
            Log::info('Flush cache');

            // Map authorId to coauthors
            if (Cache::has('co_authors_map')) {
                $coAuthorsMap = Cache::get('co_authors_map');
            } else {
                $coAuthors = DB::select('SELECT * FROM co_authors');
                $coAuthorsMap = [];
                foreach ($coAuthors as $key => $value) {
                    $value = (array) $value;
                    $firstId = $value['first_author_id'];
                    $secondId = $value['second_author_id'];
                    if (isset($coAuthorsMap[$firstId])) {
                        array_push($coAuthorsMap[$firstId], $value);
                    } else {
                        $coAuthorsMap[$firstId] = [$value];
                    }
                    if (isset($coAuthorsMap[$secondId])) {
                        array_push($coAuthorsMap[$secondId], $value);
                    } else {
                        $coAuthorsMap[$secondId] = [$value];
                    }
                }
                Cache::put('co_authors_map', $coAuthorsMap, 200);
                \Log::info('Set coAuthors map');
                unset($coAuthors);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('TRUNCATE TABLE `candidates`');
            try {
                DB::statement('ALTER TABLE candidates DROP FOREIGN KEY candidates_co_author_id_foreign;');
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
            }

            // Add job info to databases
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'sync_candidate')");

            try {
                CoAuthor::chunk(5000, function ($coAuthorsPart) use ($coAuthorsMap) {
                    $candidates = [];

                    foreach ($coAuthorsPart as $coAuthor) {
                        $firstCoAuthors = CoAuthorHelper::collaborators($coAuthor['first_author_id'], 
                                $coAuthorsMap);
                        $secondCoAuthors = CoAuthorHelper::collaborators($coAuthor['second_author_id'],
                                $coAuthorsMap);

                        $scores = MeasureLinking::wcn_waa_wca($firstCoAuthors, $secondCoAuthors,
                                $coAuthorsMap);

                        array_push($candidates, [
                            'co_author_id' => $coAuthor->id,
                            'score_1' => $scores['wcn'],
                            'score_2' => $scores['waa'],
                            'score_3' => $scores['wca'],
                        ]);
                    }
                    Candidate::insert($candidates);
                });
            } catch (Exception $e) {
                Log::debug($e->getMessage());

            } finally {
                try {
                    // Remove job info from databases
                    \DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='sync_candidate'");
                } catch (Exception $e) {
                    \Log::debug($e->getMessage());
                }

                // Set foreign key
                DB::statement('ALTER TABLE `candidates` ADD constraint  candidates_co_author_id_foreign 
                    FOREIGN KEY  (co_author_id) REFERENCES co_authors(id);');

                // Remove from cache
                Cache::pull('co_authors_map');
            }

        } catch (Exception $e) {
            \Log::debug($e->getMessage());
        }

        
    }
}
