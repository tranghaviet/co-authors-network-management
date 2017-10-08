<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthorPaper
 * @package App\Models
 * @version October 8, 2017, 8:47 pm ICT
 *
 * @property \App\Models\Author author
 * @property \App\Models\Paper paper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection coAuthors
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property integer author_id
 * @property integer paper_id
 */
class AuthorPaper extends Model
{

    public $table = 'author_paper';
    
    public $timestamps = false;

    public $fillable = [
        'author_id',
        'paper_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'author_id' => 'integer',
        'paper_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function author()
    {
        return $this->belongsTo(\App\Models\Author::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paper()
    {
        return $this->belongsTo(\App\Models\Paper::class);
    }
}