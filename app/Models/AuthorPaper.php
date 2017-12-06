<?php

namespace App\Models;

use Laravel\Scout\Searchable;
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
    use Searchable;

    public $table = 'author_paper';

    public $timestamps = false;

    public $fillable = [
        'author_id',
        'paper_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'author_id' => 'float',
        'paper_id' => 'string',
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
        $arr = [
            'id' => $this->author_id . '_' . $this->paper_id,
            'author' => $this->author['given_name'] . ' ' . $this->author['surname'],
            'paper' => $this->paper['title'],
        ];

        return $arr;
    }

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
