<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class KeywordPaper.
 *
 * @version October 11, 2017, 5:06 pm ICT
 *
 * @property \App\Models\Keyword keyword
 * @property \App\Models\Paper paper
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property int keyword_id
 * @property string paper_id
 */
class KeywordPaper extends Pivot
{
    public $table = 'keyword_paper';

    public $timestamps = false;

    public $fillable = [
        'keyword_id',
        'paper_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'keyword_id' => 'integer',
        'paper_id'   => 'string',
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
    public function keyword()
    {
        return $this->belongsTo(\App\Models\Keyword::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paper()
    {
        return $this->belongsTo(\App\Models\Paper::class);
    }
}
