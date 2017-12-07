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
use App\Models\KeywordPaper;


class ImportPaperController extends Controller
{
	
	public function view_upload_papers(){
		return view ('upload.upload_papers');
	}


	public function upload_papers(Request $request){
		
		if(Input::hasFile('file')){
			$path = Input::file('file')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();
			// $data->toArray();
			if(!empty($data) && $data->count()){
				dd($data->count());

				foreach ($data as $key => $value) {

					if(!empty($value->id)){

						// thêm bài báo
						ImportPaper::insert_papers($value->id,$value->title,$value->coverdate,$value->abstract,$value->url,$value->issn);
						
						// Tách nhóm từ khóa thành nhiều từ khóa 
						$keywords = preg_split('/,\s*/', trim($value->keywords), -1, PREG_SPLIT_NO_EMPTY);
						
						// với mỗi từ khóa thêm vào cùng với bài báo: keyword-paper
						foreach ($keywords as $keyword)
						{
							ImportPaper::handle_keywords($value->id, $keyword);
						}
						
					}
				}
				
			}
		}
	}
}
