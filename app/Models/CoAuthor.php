<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CoAuthor
 * @package App\Models
 * @version October 8, 2017, 9:23 pm ICT
 *
 * @property \App\Models\Author author
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection Candidate
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property integer first_author_id
 * @property integer second_author_id
 */
class CoAuthor extends Model
{

    public $table = 'co_authors';
    
    public $timestamps = false;

    public $fillable = [
        'first_author_id',
        'second_author_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_author_id' => 'integer',
        'second_author_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function candidates()
    {
        return $this->hasMany(\App\Models\Candidate::class);
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
