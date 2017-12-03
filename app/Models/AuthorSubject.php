<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class AuthorSubject.
 * @package App\Models
 * @version November 30, 2017, 12:25 am +07
 *
 * @property \App\Models\Author author
 * @property \App\Models\Subject subject
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property integer subject_id
 */

class AuthorSubject extends Pivot
{
    public $table = 'author_subject';
    public $timestamps = false;

    public $fillable = [
        'author_id',
        'subject_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'author_id' => 'integer',
        'subject_id' => 'integer',
    ];

    /**
     * Validation rules.
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
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }
}
