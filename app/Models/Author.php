<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Author.
 * @package App\Models
 * @version October 8, 2017, 7:41 pm ICT
 *
 * @property string given_name
 * @property string surname
 * @property string email
 * @property string url
 * @property integer university_id
 */
class Author extends Model
{
    use Eloquence;

    protected $searchableColumns = ['given_name', 'surname'];

    public $table = 'authors';

    public $timestamps = false;

    public $fillable = [
        'id',
        'given_name',
        'surname',
        'email',
        'university_id',
        'url',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'float',
        'given_name' => 'string',
        'surname' => 'string',
        'email' => 'string',
        'university_id' => 'integer',
        'url' => 'string',
    ];

    protected $hidden = ['pivot'];

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
    public function university()
    {
        // return $this->belongsTo(\App\Models\University::class);
        return $this->belongsTo(\App\Models\University::class, 'university_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function papers()
    {
        return $this->belongsToMany(\App\Models\Paper::class, 'author_paper');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|Paper|null $papers papers that keywords belongs to
     * @param array $columns
     * @return \Illuminate\Support\Collection|static Co-authors has any joint paper with this Author.
     */
    public function collaborators($papers, $columns = ['*'])
    {
        $collaborators = collect();
        $authorIds = [];

        foreach ($papers as $paper) {
            $authors = $paper->authors()->where('id', '!=', $this->id)
                ->whereNotIn('id', $authorIds)->get($columns);

            $authorIds = array_merge($authorIds, $authors->pluck('id')->toArray());

            $collaborators = $collaborators->merge($authors);
        }

        return $collaborators;
    }
}
