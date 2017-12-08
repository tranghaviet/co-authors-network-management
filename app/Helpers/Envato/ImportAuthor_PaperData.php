<?php
namespace App\Helpers\Envato;

use App\Models\Keyword;
use App\Models\KeywordPaper;

use App\Models\Paper;
use App\Models\Author;
use App\Models\AuthorPaper;

class ImportAuthor_PaperData
{

    public static function check_paper_exists($paper_id)
    {

        if (Paper::where('id', 'like', '%'.$paper_id.'%')->exists()){
            return true; 
        }
        else 
        {
            return false;
        }

    }

    public static function check_author_exitst($author_id)
    {

        if (Author::where(['id' => $author_id])->exists()){
            return true; 
        }
        else 
        {
            return false;
        }
    }

    public static function insert_link($author_id, $paper_id) 
    {
        if(!AuthorPaper::where([['author_id','=',$author_id],['paper_id','=',$paper_id]])->exists())
            {
                $author_paper=new AuthorPaper;
                $author_paper->author_id=$author_id;
                $author_paper->paper_id=$paper_id;
                $author_paper->save();
            }
        }
    }