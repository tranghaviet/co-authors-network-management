<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CoAuthorPaper
 * @package App\Models
 * @version October 11, 2017, 5:06 pm ICT
 *
 * @property \App\Models\CoAuthor coAuthor
 * @property \App\Models\Paper paper
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property integer co_author_id
 * @property string paper_id
 */
class CoAuthorPaper extends Pivot
{
    public $table = 'co_author_paper';

    public $timestamps = false;

    public $fillable = [
        'co_author_id',
        'paper_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'co_author_id' => 'integer',
        'paper_id' => 'string'
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
    public function coAuthor()
    {
        return $this->belongsTo(\App\Models\CoAuthor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paper()
    {
        return $this->belongsTo(\App\Models\Paper::class);
    }
}
