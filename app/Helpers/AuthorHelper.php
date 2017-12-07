<?php

namespace App\Helpers;

use App\Models\Author;
use App\Models\CoAuthor;

class AuthorHelper
{

    /**
     * @param int $authorId Id of the author
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function coAuthors($authorId)
    {
        return CoAuthor::where('first_author_id', $authorId)
            ->orWhere('second_author_id', $authorId);
    }

    /**
     * @param int|Author $author Id of the author
     * @param array $columns columns to get
     * @param \App\Models\Paper|null $papers papers that keywords belongs to
     * @return \Illuminate\Support\Collection|static
     */
    public static function keywords($author, $columns = ['*'], $papers = null)
    {
        if (is_numeric($author)) {
            $author = self::where('id', $author)->first(['id']);
        }

        if (is_null($papers)) {
            $papers = $author->papers()->get(['id']);
        }

        $keywords = collect();
        $keywordIds = [];

        foreach ($papers as $paper) {
            $paperKeywords = $paper->keywords()->whereNotIn('id', $keywordIds)->get($columns);
            $keywordIds = array_merge($keywordIds, $paperKeywords->pluck('id')->toArray());

            $keywords = $keywords->merge($paperKeywords);
        }

        return $keywords;
    }
}
