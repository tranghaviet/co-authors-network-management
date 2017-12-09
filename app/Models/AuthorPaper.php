<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class AuthorPaper.
 * @package App\Models
 * @version October 8, 2017, 8:47 pm ICT
 *
 * @property string author_id
 * @property string paper_id
 */
class AuthorPaper extends Pivot
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'author_id' => 'float',
        'paper_id' => 'string',
    ];

    public $table = 'author_paper';

    public $timestamps = false;

    public $fillable = [
        'author_id',
        'paper_id',
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
