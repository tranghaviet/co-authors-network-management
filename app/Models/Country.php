<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Country.
 * @package App\Models
 * @version October 11, 2017, 3:37 pm ICT
 *
 * @property string name
 */
class Country extends Model
{
    public $table = 'countries';

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

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cities()
    {
        return $this->hasMany(\App\Models\City::class);
    }
}
