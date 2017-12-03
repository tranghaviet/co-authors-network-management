<?php

namespace App\Models;

use Eloquent as Model;
use Laravel\Scout\Searchable;

/**
 * Class CoAuthor.
 * @package App\Models
 * @version October 8, 2017, 9:23 pm ICT
 *
 * @property string first_author_id
 * @property string second_author_id
 * @property int no_of_mutual_authors
 * @property int no_of_joint_papers
 * @property int no_of_joint_subjects
 * @property int no_of_joint_keywords
 */
class CoAuthor extends Model
{
    use Searchable;

    public $table = 'co_authors';

    public $timestamps = false;

    public $fillable = [
        'first_author_id',
        'second_author_id',
        'no_of_mutual_authors',
        'no_of_joint_papers',
        'no_of_joint_subjects',
        'no_of_joint_keywords',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'float',
        'first_author_id' => 'string',
        'second_author_id' => 'string',
        'no_of_mutual_authors' => 'integer',
        'no_of_joint_papers' => 'integer',
        'no_of_joint_subjects' => 'integer',
        'no_of_joint_keywords' => 'integer',
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
        $a = [
            'id' => $this->id,
        ];

        $a['first_author'] = $this->firstAuthor['given_name'] . ' ' .
            $this->firstAuthor['surname'] . ' ' . $this->firstAuthor->university['name'];
        $a['second_author'] = $this->secondAuthor['given_name'] . ' ' .
            $this->secondAuthor['surname'] . ' ' . $this->secondAuthor->university['name'];

        return $a;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function candidate()
    {
        return $this->hasOne(\App\Models\Candidate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function coAuthorPaper()
    {
        return $this->hasMany(\App\Models\CoAuthorPaper::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function papers()
    {
        return $this->belongsToMany(\App\Models\Paper::class, 'co_author_paper');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function firstAuthor()
    {
        return $this->hasOne(\App\Models\Author::class, 'id', 'first_author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function secondAuthor()
    {
        return $this->hasOne(\App\Models\Author::class, 'id', 'second_author_id');
    }

    /**
     * @param $first_author_id
     * @param $second_author_id
     * @return int no_of_joint_papers of the two authors or null.
     */
    public static function noOfJointPaper($first_author_id, $second_author_id)
    {
        $result = self::where([
            'first_author_id' => $first_author_id,
            'second_author_id' => $second_author_id,
        ])
            ->orWhere([
                'first_author_id' => $second_author_id,
                'second_author_id' => $first_author_id,
            ])
            ->first(['no_of_joint_papers'])['no_of_joint_papers'];

        return $result ?: 0;
    }

    /**
     * Get co-author with their info in authors table.
     *
     * @param float $authorId author id
     * @param array $columns fields to get
     * @return \Illuminate\Support\Collection|static
     */
    public static function collaborators($authorId, $columns = ['*'])
    {
        $coAuthors = self::coAuthors($authorId, ['first_author_id', 'second_author_id',]);

        $collaboratorIds = [];

        foreach ($coAuthors as $coAuthor) {
            if ($coAuthor->first_author_id == $authorId) {
                array_push($collaboratorIds, $coAuthor->second_author_id);
            } else {
                array_push($collaboratorIds, $coAuthor->first_author_id);
            }
        }

        return Author::whereIn('id', $collaboratorIds)->get($columns);
    }

    /**
     * Get co-author with info in co_authors table.
     *
     * @param float $authorId author id
     * @param array $columns fields to get
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function coAuthors($authorId, $columns = ['*'])
    {
        return self::where([
            ['first_author_id', '=', $authorId],
            ['no_of_joint_papers', '>', 0],
        ])->orWhere([
            ['second_author_id', '=', $authorId],
            ['no_of_joint_papers', '>', 0],
        ])->get($columns);
    }

    /**
     * Get co-author with some following info: id, no_of_joint_papers, no_of_mutual_authors, no_of_joint_subjects, no_of_joint_keywords.
     *
     * @param float $authorId author id
     * @param array $columns fields to get
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function coAuthorWithoutInfo($authorId, $columns = ['*'])
    {
        $coAuthors = self::coAuthors($authorId, $columns);

        if (! empty($coAuthors)) {
            for ($i = 0; $i < $coAuthors->count(); $i++) {
                if ($coAuthors[$i]->first_author_id == $authorId) {
                    $coAuthors[$i]['id'] = $coAuthors[$i]->first_author_id;
                    unset($coAuthors[$i]->first_author_id);
                } else {
                    $coAuthors[$i]['id'] = $coAuthors[$i]->second_author_id;
                    unset($coAuthors[$i]->second_author_id);
                }
            }
        }

        return $coAuthors;
    }
}
