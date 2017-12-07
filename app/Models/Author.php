<?php

namespace App\Models;

use Laravel\Scout\Searchable;
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
    use Searchable;

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
        $a = [
            'id' => $this->id,
            'name' => $this->given_name . ' ' . $this->surname,
        ];

        $a['university'] = $this->university['name'];
//        $papers = $this->papers()->get(['title'])->map(function ($paper) {
//            return $paper['title'];
//        });
//        $a['papers'] = implode(' ', $papers->toArray());

        return $a;
    }

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
