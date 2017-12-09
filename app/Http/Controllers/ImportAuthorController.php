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
				Cache::put('author_lines', $data, 20);

				$limit = 500;	
				$i = 0;
				while(true) {
					$offset = $i * $limit;
					$l = $n - $offset < $limit ? $n - $offset : $limit;
					if ($offset >= $n) {
						break;
					}
					dump('start import authors with limit '.strval($l).' and offset '. strval($offset) .'.');
					// $this->dispatch(new ImportAuthors($l, $offset));
					$process = new Process('php ../artisan import:authors --offset='. strval($offset) .' '. '--limit='. strval($l) .'');
      				$process->start();
      				
      				$i++;
				}
				dump('all');

			}
		}
	}
}
