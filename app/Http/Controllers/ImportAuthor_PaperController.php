<?php

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Excel;
use App\Http\Requests;
use ImportAuthor_Paper;
use App\Models\Paper;
use App\Models\AuthorPaper;
use App\Models\Keyword;
use App\Models\KeywordPaper;

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


				if(!empty($data) && $data->count())
				{
					foreach ($data as $key => $value)
					{

						if(!empty($value->authorid))
						{
							{
								// dd("add");
								// dd($value->paperid);
								// dd(Paper::where('id', 'like', '%'.$value->paperid.'%')->exists());
								// dd(AuthorPaper::where([['author_id','=',$value->authorid],['paper_id', 'like','%'.$value->paperid.'%']])->exists());

								// if(!empty($value->authorid))
								// {
								// dd(((ImportAuthor_Paper::check_paper_exists($value->paperid))&&(ImportAuthor_Paper::check_author_exitst($value->authorid))));
								if(ImportAuthor_Paper::check_paper_exists($value->paperid)&&ImportAuthor_Paper::check_author_exitst($value->authorid))
									{
										ImportAuthor_Paper::insert_link($value->authorid,$value->paperid);
									}

								// 	}
								}
							}
						}

					}

				}

			}
		}