<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Paper.
 * @package App\Models
 * @version October 8, 2017, 8:05 pm ICT
 *
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection coAuthors
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string title
 * @property datetime cover_date
 * @property string abstract
 * @property string url
 * @property string issn
 */
class Paper extends Model
{
    use Searchable;

    public $table = 'papers';

    public $timestamps = false;

    public $fillable = [
        'id',
        'title',
        'cover_date',
        'abstract',
        'url',
        'issn',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'cover_date' => 'datetime',
        'abstract' => 'string',
        'url' => 'string',
        'issn' => 'string',
    ];

    protected $hidden = [
        'pivot',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * Get the indexable data array for the model. (TNTSearch).
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $a = [
            'id' => $this->id,
            'title' => $this->title,
            'issn' => $this->issn,
        ];

//        $authors = $this->authors()->get(['given_name', 'surname'])->map(function ($author) {
//            return $author['given_name'] . ' ' . $author['surname'];
//        });
//        $a['authors'] = implode(' ', $authors->toArray());

        $keywords = $this->keywords()->get(['content'])->map(function ($keyword) {
            return $keyword['content'];
        });
        $a['keywords'] = implode(' ', $keywords->toArray());

        return $a;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function authors()
    {
        return $this->belongsToMany(\App\Models\Author::class, 'author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function coAuthors()
    {
        return $this->belongsToMany(\App\Models\CoAuthor::class, 'co_author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function keywords()
    {
        return $this->belongsToMany(\App\Models\Keyword::class, 'keyword_paper');
    }
}
