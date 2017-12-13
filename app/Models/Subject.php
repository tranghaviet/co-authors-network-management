<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Subject.
 * @package App\Models
 * @version October 11, 2017, 4:58 pm ICT
 *
 * @property string name
 */
class Subject extends Model
{
    public $table = 'subjects';

    public $timestamps = false;

    public $fillable = [
        'name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    protected $hidden = ['pivot'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function authors()
    {
        return $this->belongsToMany(\App\Models\Author::class, 'author_subject');
    }
}
