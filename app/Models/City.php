<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class City.
 *
 * @version October 11, 2017, 2:31 pm ICT
 *
 * @property \App\Models\Country country
 * @property \Illuminate\Database\Eloquent\Collection authorPaper
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property \Illuminate\Database\Eloquent\Collection University
 * @property string name
 * @property int country_id
 */
class City extends Model
{
    public $table = 'cities';

    public $timestamps = false;

    public $fillable = [
        'name',
        'country_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'country_id' => 'integer',
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
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function universities()
    {
        return $this->hasMany(\App\Models\University::class);
    }
}
