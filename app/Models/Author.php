<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Sofa\Eloquence\Eloquence;
use Watson\Rememberable\Rememberable;

/**
 * Class Author.
 * @package App\Models
 * @version October 8, 2017, 7:41 pm ICT
 *
 * @property \Illuminate\Database\Eloquent\Collection authorSubject
 * @property \Illuminate\Database\Eloquent\Collection coAuthorPaper
 * @property \Illuminate\Database\Eloquent\Collection keywordPaper
 * @property string given_name
 * @property string surname
 * @property string email
 * @property string url
 * @property integer university_id
 */
class Author extends Model
{
    use Searchable;
//    use Eloquence;
    use Rememberable;

    protected $rememberFor = 10;

    public $table = 'authors';

    public $timestamps = false;

    public $fillable = [
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
        'id' => 'string',
        'given_name' => 'string',
        'surname' => 'string',
        'email' => 'string',
        'university_id' => 'integer',
        'url' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * Default searchable columns (Eloquence).
     *
     * @var array
     */
    protected $searchableColumns = [
        'given_name' => 10,
        'surname' => 10,
        'email' => 10,
        'university.name' => 7,
        'papers.title' => 5,
    ];

    /**
     * Get the indexable data array for the model. (TNTSearch).
     *
     * @return array
     */
    public function toSearchableArray()
    {
//        $a = $this->toArray();
//        unset($a['url']);
        $a = [
            'id' => $this->id,
            'name' => $this->given_name.' '.$this->surname,
            'email' => $this->email,
        ];

        $a['university'] = $this->university['name'];
        $papers = $this->papers()->get(['title'])->map(function ($paper) {
            return $paper['title'];
        });
        $a['papers'] = implode(' ', $papers->toArray());

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

    public function collaborators($columns = ['*'])
    {
        $papers = $this->papers;
        $collaborators = collect();

        foreach ($papers as $paper) {
            $ids = $collaborators->map(function ($author) {
                return $author->id;
            });
            $authors = $paper->authors()->where('id', '!=', $this->id)
                ->whereNotIn('id', $ids)->get($columns);
            $collaborators = $collaborators->merge($authors);
        }

        return $collaborators;
    }
}
