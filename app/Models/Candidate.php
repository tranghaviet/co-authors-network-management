<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Candidate.
 * @package App\Models
 * @version October 8, 2017, 9:45 pm ICT
 *
 * @property \App\Models\CoAuthor coAuthor
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property integer co_author_id
 * @property smallInteger no_of_mutual_authors
 * @property smallInteger no_of_joint_papers
 * @property smallInteger no_of_joint_subjects
 * @property smallInteger no_of_joint_keywords
 * @property float score_1
 * @property float score_2
 * @property float score_3
 */
class Candidate extends Model
{
    public $table = 'candidates';

    public $timestamps = false;

    public $fillable = [
        'co_author_id',
        'no_of_mutual_authors',
        'no_of_joint_papers',
        'no_of_joint_subjects',
        'no_of_joint_keywords',
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
        'co_author_id' => 'integer',
        'no_of_mutual_authors' => 'integer',
        'no_of_joint_papers' => 'integer',
        'no_of_joint_subjects' => 'integer',
        'no_of_joint_keywords' => 'integer',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function coAuthor()
    {
        return $this->belongsTo(\App\Models\CoAuthor::class);
    }
}
