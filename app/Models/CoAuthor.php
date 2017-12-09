<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CoAuthor.
 * @package App\Models
 * @version October 8, 2017, 9:23 pm ICT
 *
 * @property string first_author_id
 * @property string second_author_id
 * @property int no_of_mutual_authors
 * @property int no_of_joint_papers
 */
class CoAuthor extends Model
{
    public $table = 'co_authors';

    public $timestamps = false;

    public $fillable = [
        'first_author_id',
        'second_author_id',
        'no_of_mutual_authors',
        'no_of_joint_papers',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'first_author_id' => 'float',
        'second_author_id' => 'float',
        'no_of_mutual_authors' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function candidate()
    {
        // return $this->hasOne(\App\Models\Candidate::class, 'co_author_id', 'id');
        return $this->belongsTo(\App\Models\Candidate::class, 'id', 'co_author_id');
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
}
