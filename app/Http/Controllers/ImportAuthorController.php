<?php 
namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\City;
use ImportAuthor;
use Excel;
use App\Models\Author;

class ImportAuthorController extends Controller
{
	public function view_upload_authors()
	{
		return view('upload.upload_authors');
	}
	public function upload_authors()
	{
		$UNKNOWN='UNKNOWN';
		if(Input::hasFile('file')){
			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count())
			{
				foreach ($data as $key => $value)
				{
					if(!empty($value->id))
					{
						$affiliation = preg_split('/,\s*/', $value->affiliation);
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

						$id = $value->id;
						$surname = $value->surname;
						$given_name = $value->givenname;
						$email = $value->email;
						$url = $value->url;
						ImportAuthor::insert_authors( $id, $surname, $given_name, $email, $url, $university_id);
						ImportAuthor::handle_subjects( $id, $value->subjects);
					}
				}

			}
		}
	}
}
