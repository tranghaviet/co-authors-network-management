<?php

namespace App\Helpers;

use App\Models\Author;
use App\Models\CoAuthor; 

class CoAuthorHelper
{

    /**
     * @param $first_author_id
     * @param $second_author_id
     * @return int no_of_joint_papers of the two authors or null.
     */
    public static function noOfJointPaper($first_author_id, $second_author_id)
    {
        $result = CoAuthor::where([
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
        return CoAuthor::where([
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
