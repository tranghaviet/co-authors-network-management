<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Excel;
use App\Http\Requests;
use ImportPaper;
use App\Models\Paper;
use App\Models\Keyword;
use Cache;
use Flash;
use App\Models\KeywordPaper;
use App\Models\AuthorSubject;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImportPaperController extends Controller
{
	public function view_upload_papers(){
		return view ('upload.upload_papers');
	}

	public function upload_papers(Request $request)
	{
		if(Input::hasFile('file')){

			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get()->toArray();
			$n = count($data);
			if(!empty($data) && $n)
			{
				// dump('Put papers data to cache');
				Cache::put('paper_lines', $data, 20);

				$limit = 250;	
				$i = 0;
				while(true) {
					$offset = $i * $limit;
					$l = $n - $offset < $limit ? $n - $offset : $limit;
					if ($offset >= $n) {
						break;
					}
					// dump('start import papers with limit '.strval($l).' and offset '. strval($offset) .'.');
					$process = new Process('php ../artisan import:papers --offset='. strval($offset) .' '. '--limit='. strval($l) .'');
      				$process->start();
      				
      				$i++;
				}
							
				Flash::info('In processing. Please wait');

				return redirect()->back();
			}
		}

	}
}
