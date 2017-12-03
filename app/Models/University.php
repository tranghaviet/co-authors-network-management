<?php

namespace App\Models;

use Eloquent as Model;
use Laravel\Scout\Searchable;
use Watson\Rememberable\Rememberable;

/**
 * Class University.
 * @package App\Models
 * @version October 11, 2017, 1:54 pm ICT
 *
 * @property string name
 * @property integer city_id
 */
class University extends Model
{
    use Searchable;
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
        'id' => 'integer',
        'name' => 'string',
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
     * Get the indexable data array for the model. (TNTSearch).
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

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
