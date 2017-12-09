<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Excel;
use Cache;
use Log;
use DB;
use App\Http\Requests;
use ImportAuthor_Paper;
use App\Models\Paper;
use App\Models\AuthorPaper;
use App\Models\Keyword;
use App\Models\KeywordPaper;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImportAuthor_PaperController extends Controller
{
	public function view_upload_authors_papers()
	{
		return view ('upload.upload_authors_papers');
	}

	public function upload_authors_papers(Request $request)
	{
		if(Input::hasFile('file'))
		{
			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();

			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get()->toArray();
			$n = count($data);
			if(!empty($data) && $n)
			{
				dump('Put author paper data to cache');
				Cache::put('author_paper_lines', $data, 200);

				$limit = 500;	
				$i = 0;
				while(true) {
					$offset = $i * $limit;
					$l = $n - $offset < $limit ? $n - $offset : $limit;
					if ($offset >= $n) {
						break;
					}
					dump('start import author paper with limit '.strval($l).' and offset '. strval($offset) .'.');
					$process = new Process('php ../artisan import:author_paper --offset='. strval($offset) .' '. '--limit='. strval($l) .'');
      				$process->start();
      				
      				$i++;
				}
				dump('all has started ');
			}
		}
	}
}