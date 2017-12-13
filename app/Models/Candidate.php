<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Candidate.
 * @package App\Models
 * @version October 8, 2017, 9:45 pm ICT
 *
 * @property \App\Models\CoAuthor coAuthor
 * @property float co_author_id
 * @property float score_1
 * @property float score_2
 * @property float score_3
 */
class Candidate extends Model
{
    protected $primaryKey = 'co_author_id';

    public $table = 'candidates';

    public $timestamps = false;

    public $fillable = [
        'co_author_id',
        'score_1',
        'score_2',
        'score_3',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'co_author_id' => 'string',
        'score_1' => 'float',
        'score_2' => 'float',
        'score_3' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     **/
    public function coAuthor()
    {
        // return $this->belongsTo(\App\Models\CoAuthor::class, 'co_author_id', 'id');
        return $this->hasOne(\App\Models\CoAuthor::class, 'id', 'co_author_id');
    }
}
