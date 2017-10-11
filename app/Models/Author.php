<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Author
 * @package App\Models
 * @version October 8, 2017, 7:41 pm ICT
 *
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string given_name
 * @property string surname
 * @property string email
 * @property string url
 */
class Author extends Model
{
    public $table = 'authors';

    public $timestamps = false;

    public $fillable = [
        'given_name',
        'surname',
        'email',
        'url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'given_name' => 'string',
        'surname' => 'string',
        'email' => 'string',
        'url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function subjects()
    {
        return $this->belongsToMany(\App\Models\Subject::class, 'author_subject');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    // public function university()
    // {
        // return $this->belongsTo(\App\Models\University::class);
    // }
}
