<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cache;
use ImportAuthor;
use Artisan;
use Log;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImportAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:authors {--offset=0} {--limit=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $UNKNOWN='UNKNOWN';
        $offset = $this->option('offset');
        $limit = $this->option('limit');

        // Add job info to databases
        try {
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'author')");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        $author_lines = array_slice(Cache::get('author_lines'), $offset, $limit);
        
        foreach ($author_lines as $key => $value)
        {
            if(!empty($value['id']))
            {

                $affiliation = preg_split('/,\s*/', $value['affiliation']);
                $n = count($affiliation);
                $university = array_key_exists(0, $affiliation) ? $affiliation[0] : $UNKNOWN;
                if (array_key_exists($n - 1, $affiliation) && $n - 1 > 0) {
                   $country = $affiliation[$n - 1];
                } else {
                   $country = $UNKNOWN;
                }
                if (array_key_exists($n - 2, $affiliation) && $n - 2 > 0) {
                   $city = $affiliation[$n - 2];

                } else {
                   $city = $UNKNOWN;
                }

                $country_id = ImportAuthor::handle_country($country, $UNKNOWN);

                if (!$country_id) {
                   continue;
                }
                // City
                $city_id = ImportAuthor::handle_city($city, $country_id, $UNKNOWN);

                if (!$city_id) {
                   continue;
                }
                // University
                $university_id =ImportAuthor:: handle_university($university, $city_id, $UNKNOWN);
                if (!$university_id) {
                   continue;
                }

                $id = $value['id'];
                $surname = $value['surname'];
                $given_name = $value['givenname'];
                $email = $value['email'];
                $url = $value['url'];
                ImportAuthor::insert_authors( $id, $surname, $given_name, $email, $url, $university_id);
                ImportAuthor::handle_subjects( $id, $value['subjects']);
            }
        }

        // Remove job info from databases
        try {
            \DB::statement("DELETE FROM importjobs WHERE pid = ".getmypid()." AND type='author'");
            $c = count(\DB::select("SELECT * FROM importjobs WHERE type='author'"));
            if ($c == 0) {
                // All job done
                Cache::pull('author_lines');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        // Artisan::call('author:re-index', ['--university' => true]);
        $process = new Process('php ../artisan author:re-index --university');
        $process->start();  
    }
}