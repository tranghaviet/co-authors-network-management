<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paper.
 * @package App\Models
 * @version October 8, 2017, 8:05 pm ICT
 *
 * @property string title
 * @property datetime cover_date
 * @property string abstract
 * @property string url
 * @property string issn
 */
class Paper extends Model
{
    use Eloquence;

    protected $searchableColumns = ['title'];

    public $table = 'papers';

    public $timestamps = false;

    public $fillable = [
        'id',
        'title',
        'cover_date',
        'abstract',
        'url',
        'issn',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'cover_date' => 'datetime',
        'abstract' => 'string',
        'url' => 'string',
        'issn' => 'string',
    ];

    protected $hidden = [
        'pivot',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function authors()
    {
        return $this->belongsToMany(\App\Models\Author::class, 'author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function coAuthors()
    {
        return $this->belongsToMany(\App\Models\CoAuthor::class, 'co_author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function keywords()
    {
        return $this->belongsToMany(\App\Models\Keyword::class, 'keyword_paper');
    }
}
