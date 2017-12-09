<?php 
namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\City;
use App\Jobs\ImportAuthors;
use ImportAuthor;
use Excel;
use Cache;
use Log;
use DB;
use App\Models\Author;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImportAuthorController extends Controller
{

	public function view_upload_authors()
	{
		return view('upload.upload_authors');
	}
	public function upload_authors()
	{

		if(Input::hasFile('file')){

			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get()->toArray();
			$n = count($data);
			Log::info($n);
			if(!empty($data) && $n)
			{
				dump('Put authors data to cache');
				// Cache::put('author_lines', $data, 20);
				// dump(Cache::get('author_lines'));
				$json = DB::connection()->getPdo()->quote(json_encode($data));
				DB::statement("DELETE FROM `cache` WHERE `key`='authors';");
				DB::statement("INSERT INTO `cache` (`key`, `value`) VALUES ('authors', {$json});");

				// $data2 = DB::select("SELECT `value` FROM `cache` WHERE `key`='authors';");
				// dump(json_decode($data2[0]->value));
				// dd(array_slice(json_decode($data2[0]->value), 0, 2));
				// dd($data->count());

				$limit = 500;	
				$i = 0;
				while(true) {
					$offset = $i * $limit;
					$l = $n - $offset < $limit ? $n - $offset : $limit;
					if ($offset >= $n) {
						break;
					}
					dump('start import authors with limit '.strval($l).' and offset '. strval($offset) .'.');
					$this->dispatch(new ImportAuthors($l, $offset));
					// $process = new Process('php ../../../artisan import:authors {--offset='. strval($offset) .'}'. '{limit='. strval($l) .'}');
     //  				$process->start();
      				
      				$i++;
				}
				dump('all');

				// foreach ($data as $key => $value)
				// {
				// 	if(!empty($value->id))
				// 	{
    //             // if(!Author::where(['id' => $id])->exists())
				// 		$affiliation = preg_split('/,\s*/', $value->affiliation);
				// 		$n = count($affiliation);
				// 		$university = array_key_exists(0, $affiliation) ? $affiliation[0] : $UNKNOWN;
				// 		if (array_key_exists($n - 1, $affiliation) && $n - 1 > 0) {
				// 			$country = $affiliation[$n - 1];
				// 		} else {
				// 			$country = $UNKNOWN;
				// 		}
				// 		if (array_key_exists($n - 2, $affiliation) && $n - 2 > 0) {
				// 			$city = $affiliation[$n - 2];

				// 		} else {
				// 			$city = $UNKNOWN;
				// 		}

				// 		$country_id = ImportAuthor::handle_country($country, $UNKNOWN);

				// 		if (!$country_id) {
				// 			continue;
				// 		}
    //                 // City
				// 		$city_id = ImportAuthor::handle_city($city, $country_id, $UNKNOWN);

				// 		if (!$city_id) {
				// 			continue;
				// 		}
    //                 // University
				// 		$university_id =ImportAuthor:: handle_university($university, $city_id, $UNKNOWN);
				// 		if (!$university_id) {
				// 			continue;
				// 		}

				// 		$id = $value->id;
				// 		$surname = $value->surname;
				// 		$given_name = $value->givenname;
				// 		$email = $value->email;
				// 		$url = $value->url;
				// 		ImportAuthor::insert_authors( $id, $surname, $given_name, $email, $url, $university_id);
				// 		ImportAuthor::handle_subjects( $id, $value->subjects);
				// 	}
				// }

			}
		}
	}
}
