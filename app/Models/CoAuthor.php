<?php

namespace App\Models;

use Eloquent as Model;
use Laravel\Scout\Searchable;

/**
 * Class CoAuthor.
 * @package App\Models
 * @version October 8, 2017, 9:23 pm ICT
 *
 * @property \App\Models\Author author
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection Candidate
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string first_author_id
 * @property string second_author_id
 * @property int no_of_mutual_authors
 * @property int no_of_joint_papers
 * @property int no_of_joint_subjects
 * @property int no_of_joint_keywords
 */
class CoAuthor extends Model
{
    use Searchable;

    public $table = 'co_authors';

    public $timestamps = false;

    public $fillable = [
        'first_author_id',
        'second_author_id',
        'no_of_mutual_authors',
        'no_of_joint_papers',
        'no_of_joint_subjects',
        'no_of_joint_keywords',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_author_id' => 'string',
        'second_author_id' => 'string',
        'no_of_mutual_authors' => 'integer',
        'no_of_joint_papers' => 'integer',
        'no_of_joint_subjects' => 'integer',
        'no_of_joint_keywords' => 'integer',
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
        $a = [
            'id' => $this->id,
        ];

        $a['first_author'] = $this->firstAuthor['given_name'] . ' ' .
            $this->firstAuthor['surname'] . ' ' . $this->firstAuthor->university['name'];
        $a['second_author'] = $this->secondAuthor['given_name'] . ' ' .
            $this->secondAuthor['surname'] . ' ' . $this->secondAuthor->university['name'];

        return $a;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function candidate()
    {
        return $this->hasOne(\App\Models\Candidate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function papers()
    {
        return $this->belongsToMany(\App\Models\Paper::class, 'co_author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function firstAuthor()
    {
        return $this->hasOne(\App\Models\Author::class, 'id', 'first_author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function secondAuthor()
    {
        return $this->hasOne(\App\Models\Author::class, 'id', 'second_author_id');
    }

    public static function noOfJointPaper($first_author_id, $second_author_id)
    {
        return CoAuthor::where(compact($first_author_id, $second_author_id))->first('no_of_joint_papers');
    }
}
