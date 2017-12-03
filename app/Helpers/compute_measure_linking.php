<?php

use App\Models\CoAuthor;

const DEFAULT_COLUMNS = [
    'first_author_id',
    'second_author_id',
    'no_of_joint_papers',
];

if (! function_exists('wcn')) {
    /**
     * compute measure linking base on wcn.
     *
     * @param  float $firstAuthorId id of first author
     * @param  float $secondAuthorId id of second author
     * @return float
     */
    function wcn($firstAuthorId, $secondAuthorId)
    {
        $firstCoAuthors = CoAuthor::coAuthorWithoutInfo($firstAuthorId, DEFAULT_COLUMNS);
        $secondCoAuthors = CoAuthor::coAuthorWithoutInfo($secondAuthorId, DEFAULT_COLUMNS);
//        dump($firstCoAuthors);
//        dd($secondCoAuthors);

        $result = 0;

        foreach ($firstCoAuthors as $firstCoAuthor) {
            foreach ($secondCoAuthors as $secondCoauthor) {
                if ($firstCoAuthor->id == $secondCoauthor->id) {
                    $result += $firstCoAuthor->no_of_joint_papers + $secondCoauthor->no_of_joint_papers;
                    break;
                }
            }
        }

        return $result / 2;
    }
}

if (! function_exists('waa')) {
    /**
     * compute measure linking base on waa.
     *
     * @param  float $firstAuthorId id of first author
     * @param  float $secondAuthorId id of second author
     * @return float
     */
    function waa($firstAuthorId, $secondAuthorId)
    {
        $firstCoAuthors = CoAuthor::coAuthorWithoutInfo($firstAuthorId, DEFAULT_COLUMNS);
        $secondCoAuthors = CoAuthor::coAuthorWithoutInfo($secondAuthorId, DEFAULT_COLUMNS);

        $result = 0;
        $authorIds = [];

        foreach ($firstCoAuthors as $firstCoauthor) {
            foreach ($secondCoAuthors as $secondCoauthor) {
                if ($firstCoauthor->id == $secondCoauthor->id) {
                    array_push($authorIds, $firstCoauthor->id);
                    $result += $firstCoauthor->no_of_joint_papers + $secondCoauthor->no_of_joint_papers;
                    break;
                }
            }
        }

        if (length($authorIds) == 0) {
            return 0;
        }

        $allJointPapers = 0;

        foreach ($authorIds as $authorId) {
            foreach (CoAuthor::coAuthorWithoutInfo($authorId, DEFAULT_COLUMNS) as $coAuthor) {
                $allJointPapers += $coAuthor->no_of_joint_papers;
            }
        }

        return $result / (2 * log10($allJointPapers));
    }
}

if (! function_exists('wjc')) {
    /**
     * compute measure linking base on wjc.
     *
     * @param  float $firstAuthorId id of first author
     * @param  float $secondAuthorId id of second author
     * @return float
     */
    function wjc($firstAuthorId, $secondAuthorId)
    {
        $firstCoAuthors = CoAuthor::coAuthorWithoutInfo($firstAuthorId, DEFAULT_COLUMNS);
        $secondCoAuthors = CoAuthor::coAuthorWithoutInfo($secondAuthorId, DEFAULT_COLUMNS);

        $result = 0;
        $allJointPapers = 0;

        foreach ($firstCoAuthors as $firstCoauthor) {
            $allJointPapers += $firstCoauthor->no_of_joint_papers;

            foreach ($secondCoAuthors as $secondCoauthor) {
                $allJointPapers += $secondCoauthor->no_of_joint_papers;

                if ($firstCoauthor->id == $secondCoauthor->id) {
                    $result += $firstCoauthor->no_of_joint_papers + $secondCoauthor->no_of_joint_papers;
                    break;
                }
            }
        }

        if ($allJointPapers == 0) {
            return 0;
        }

        return $result / $allJointPapers;
    }
}

if (! function_exists('wca')) {
    /**
     * compute measure linking base on wca.
     *
     * @param  float $firstAuthorId id of first author
     * @param  float $secondAuthorId id of second author
     * @return float
     */
    function wca($firstAuthorId, $secondAuthorId)
    {
        $firstCoAuthors = CoAuthor::coAuthorWithoutInfo($firstAuthorId, DEFAULT_COLUMNS);
        $secondCoAuthors = CoAuthor::coAuthorWithoutInfo($secondAuthorId, DEFAULT_COLUMNS);

        $result = 0;
        $allJointPapers = 0;

        foreach ($firstCoAuthors as $firstCoauthor) {
            $result += $firstCoauthor->no_of_joint_papers;
        }

        foreach ($secondCoAuthors as $secondCoauthor) {
            $allJointPapers += $secondCoauthor->no_of_joint_papers;
        }

        return $result * $allJointPapers;
    }
}
