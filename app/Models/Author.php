<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;

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
    use Rememberable;

    protected $rememberFor = 10;

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
        'id' => 'string',
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
     * @param int | Author $author
     * @param array $columns
     * @return \Illuminate\Support\Collection|static Co-authors has any joint paper with this Author.
     */
    public static function collaborators($author, $columns = ['*'])
    {
        if (is_numeric($author)) {
            $author = Author::find($author);
        }

        $papers = $author->papers;
        $collaborators = collect();
        $authorIds = [];

        foreach ($papers as $paper) {
            $authors = $paper->authors()->where('id', '!=', $author->id)
                ->whereNotIn('id', $authorIds)->get($columns);

            $authorIds = array_merge($authorIds, $authors->map(function ($author) {
                return $author->id;
            })->toArray());

            $collaborators = $collaborators->merge($authors);
        }

        return $collaborators;
    }

    /**
     * @param int $authorId Id of the author
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function coAuthors($authorId)
    {
        return CoAuthor::where('first_author_id', $authorId)
            ->orWhere('second_author_id', $authorId);
    }
}
