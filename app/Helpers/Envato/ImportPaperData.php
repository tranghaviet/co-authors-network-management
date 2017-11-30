<?php
namespace App\Helpers\Envato;

use App\Models\Keyword;
use App\Models\KeywordPaper;

use App\Models\Paper;

class ImportPaperData
{

    public static function insert_papers($id, $title, $cover_date, $abstract, $url, $issn)
    {
 if (!Paper::where(['id' => $id])->exists())
    {
        $paper= new Paper;
        $paper->id= $id;
        $paper->title= $title;
        $paper->cover_date= $cover_date;
        $paper->abstract= $abstract;
        $paper->url= $url;
        $paper->issn= $issn;
        $paper->save();
    }
    }

    public static function handle_keywords($paper_id,$keyword)
    {
        if (!Keyword::where(['content' => $keyword])->exists()) {
            // If not exist, create one
         $keywordd = new Keyword;
         $keywordd->content=$keyword;
         $keywordd->save();
     }else{
        $keywordd = Keyword::where('content', '=', $keyword)->first();
    }
    
    if(!KeywordPaper::where([['keyword_id','=',$keywordd->id],['paper_id','=',$paper_id]])->exists())
        {
            $keyword_paper=new KeywordPaper;
            $keyword_paper->keyword_id=$keywordd->id;
            $keyword_paper->paper_id=$paper_id;
        }
    }
}