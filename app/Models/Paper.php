<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Paper
 * @package App\Models
 * @version October 8, 2017, 8:05 pm ICT
 *
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection coAuthors
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string title
 * @property date cover_date
 * @property string abstract
 * @property string url
 * @property string issn
 */
class Paper extends Model
{
    public $table = 'papers';

    public $timestamps = false;

    public $fillable = [
        'title',
        'cover_date',
        'abstract',
        'url',
        'issn'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'cover_date' => 'date',
        'abstract' => 'string',
        'url' => 'string',
        'issn' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

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
