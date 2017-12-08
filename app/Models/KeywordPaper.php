<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class KeywordPaper.
 * @package App\Models
 * @version October 11, 2017, 5:06 pm ICT
 *
 * @property integer keyword_id
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
        'paper_id' => 'string',
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
