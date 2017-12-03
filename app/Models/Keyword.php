<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Keyword.
 * @package App\Models
 * @version October 11, 2017, 5:05 pm ICT
 *
 * @property string content
 */
class Keyword extends Model
{
    public $table = 'keywords';

    public $timestamps = false;

    public $fillable = [
        'content',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'content' => 'string',
    ];

    protected $hidden = ['pivot'];

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
    public function papers()
    {
        return $this->belongsToMany(\App\Models\Paper::class, 'keyword_paper');
    }
}
