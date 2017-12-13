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
use Flash;
use Artisan;
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

			# Check if any importing job exists
			$importJobs = DB::select("SELECT * FROM importjobs");

			if (count($importJobs) > 0) {
				Flash::warning('Có một chức năng nhập dữ liệu đang được thực hiện, bạn vui lòng quay lại sau ít phút');
				return redirect()->back();
				
			} else {
				if(!empty($data) && $n)
				{
					// dump('Put authors data to cache');
					DB::statement('SET GLOBAL max_allowed_packet=500000000');
					Cache::put('author_lines', $data, 20);

					$numProcesses = 15.0;
					$limit = intval(ceil($n / $numProcesses));	
					$i = 0;
					while($i < $numProcesses) {
						$offset = $i * $limit;
						$l = $n - $offset < $limit ? $n - $offset : $limit;
						if ($offset >= $n) {
							break;
						}
						// dump('start import authors with limit '.strval($l).' and offset '. strval($offset) .'.');
						// $this->dispatch(new ImportAuthors($l, $offset));
						$process = new Process('php ../artisan import:authors --offset='. strval($offset) .' '. '--limit='. strval($l) .'');
	      				$process->start();
	      				
	      				$i++;
					}
				
					Flash::info('In processing. Please wait');

					return redirect()->back();
				} else {
					Flash::info('Done');
					return redirect()->back();
				}
			}
			
		} else {
			Flash::error('Nothing to import');
			return redirect()->back();
		}
	}
}
