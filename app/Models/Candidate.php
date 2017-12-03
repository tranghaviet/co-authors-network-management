<?php

namespace App\Models;

use Eloquent as Model;
use Laravel\Scout\Searchable;

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
    use Searchable;

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
        'co_author_id' => 'float',
        'score_1' => 'float',
        'score_2' => 'float',
        'score_3' => 'float',
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
        $a = ['id' => $this->id];

        $a['first_author'] = $this->coAuthor->firstAuthor['given_name'] . ' ' . $this->coAuthor->firstAuthor['surname'];
        $a['second_author'] = $this->coAuthor->secondAuthor['given_name'] . ' ' . $this->coAuthor->secondAuthor['surname'];

        return $a;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function coAuthor()
    {
        return $this->belongsTo(\App\Models\CoAuthor::class);
    }
}
