<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Cache;
use DB;
use App\Models\City;
use ImportAuthor;
use App\Models\Author;
use Log;

class ImportAuthors implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $limit;
    protected $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($limit, $offset)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $UNKNOWN = 'UNKNOWN';
            $data = DB::select("SELECT `value` FROM `cache` WHERE `key`='authors';");
            if (! count($data)) {
                return;
            }
            $data = json_decode($data[0]->value, true);
            $author_lines = array_slice($data, $this->offset, $this->limit);
            foreach ($author_lines as $key => $value) {
                if (! empty($value['id'])) {
                    // if(!Author::where(['id' => $id])->exists())
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

                    if (! $country_id) {
                        continue;
                    }
                    // City
                    $city_id = ImportAuthor::handle_city($city, $country_id, $UNKNOWN);

                    if (! $city_id) {
                        continue;
                    }
                    // University
                    $university_id = ImportAuthor:: handle_university($university, $city_id, $UNKNOWN);
                    if (! $university_id) {
                        continue;
                    }

                    $id = $value['id'];
                    $surname = $value['surname'];
                    $given_name = $value['givenname'];
                    $email = $value['email'];
                    $url = $value['url'];
                    ImportAuthor::insert_authors($id, $surname, $given_name, $email, $url, $university_id);
                    ImportAuthor::handle_subjects($id, $value['subjects']);
                }
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
