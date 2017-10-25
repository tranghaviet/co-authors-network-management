<?php

namespace App\Models;

use Eloquent as Model;
use Watson\Rememberable\Rememberable;

/**
 * Class University.
 *
 * @version October 11, 2017, 1:54 pm ICT
 *
 * @property \App\Models\City city
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection Author
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string name
 * @property int city_id
 */
class University extends Model
{
    use Rememberable;

    /**
     * Time for cache a query.
     *
     * @var int
     */
    protected $rememberFor = 30;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'      => 'integer',
        'name'    => 'string',
        'city_id' => 'integer',
    ];

    public $table = 'universities';

    public $timestamps = false;

    public $fillable = [
        'name',
        'city_id',
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
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function authors()
    {
        return $this->hasMany(\App\Models\Author::class);
    }
}
