<?php

namespace App\Models;

use Eloquent as Model;
use Sofa\Eloquence\Eloquence;

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
    use Eloquence;

    protected $searchableColumns = ['name'];

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
