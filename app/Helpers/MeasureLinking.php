<?php

namespace App\Helpers;

use App\Models\CoAuthor;

const DEFAULT_COLUMNS = [
    'first_author_id',
    'second_author_id',
    'no_of_joint_papers',
];

class MeasureLinking
{
    /**
     * compute measure linking base on wcn.
     *
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $firstCoAuthors co-authors of first author with their no_of_joint_papers
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $secondCoAuthors co-authors of second author with their no_of_joint_papers
     * @return float Sum of joint papers of all joint author.
     */
    public static function wcn($firstCoAuthors, $secondCoAuthors)
    {
        $result = 0;

        foreach ($firstCoAuthors as $firstCoAuthor) {
            foreach ($secondCoAuthors as $secondCoauthor) {
                if ($firstCoAuthor['author_id'] == $secondCoauthor['author_id']) {
                    // dump($firstCoAuthor);
                    // dump($secondCoauthor);
                    $result += $firstCoAuthor['no_of_joint_papers'] + $secondCoauthor['no_of_joint_papers'];
                    break;
                }
            }
        }

        return $result / 2;
    }

    /**
     * compute measure linking base on waa.
     *
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $firstCoAuthors co-authors of first author with their no_of_joint_papers
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $secondCoAuthors co-authors of second author with their no_of_joint_papers
     * @return float Sum of joint papers of all joint author.
     */
    public static function waa($firstCoAuthors, $secondCoAuthors)
    {
        $result = 0;
        $jointAuthorIds = [];

        foreach ($firstCoAuthors as $firstCoauthor) {
            foreach ($secondCoAuthors as $secondCoauthor) {
                if ($firstCoauthor['author_id'] == $secondCoauthor['author_id']) {
                    array_push($jointAuthorIds, $firstCoauthor['author_id']);
                    $result += $firstCoauthor['no_of_joint_papers'] + $secondCoauthor['no_of_joint_papers'];
                    break;
                }
            }
        }

        if (count($jointAuthorIds) == 0) {
            return 0;
        }

        $allJointPapers = 0;

        foreach ($jointAuthorIds as $authorId) {
            foreach (CoAuthor::coAuthorWithoutInfo($authorId, DEFAULT_COLUMNS) as $coAuthor) {
                $allJointPapers += $coAuthor['no_of_joint_papers'];
            }
        }

        return $result / (2 * log10($allJointPapers));
    }

    /**
     * compute measure linking base on wjc.
     *
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $firstCoAuthors co-authors of first author with their no_of_joint_papers
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $secondCoAuthors co-authors of second author with their no_of_joint_papers
     * @return float Sum of joint papers of all joint author.
     */
    public static function wjc($firstCoAuthors, $secondCoAuthors)
    {
        $result = 0;
        $allJointPapers = 0;

        foreach ($firstCoAuthors as $firstCoauthor) {
            $allJointPapers += $firstCoauthor->no_of_joint_papers;

            foreach ($secondCoAuthors as $secondCoauthor) {
                $allJointPapers += $secondCoauthor->no_of_joint_papers; // TODO: should this be in individual loop?

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

    /**
     * compute measure linking base on wca.
     *
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $firstCoAuthors co-authors of first author with their no_of_joint_papers
     * @param  \App\Models\CoAuthor|\Illuminate\Support\Collection $secondCoAuthors co-authors of second author with their no_of_joint_papers
     * @return float Sum of joint papers of all joint author.
     */
    public
    static function wca($firstCoAuthors, $secondCoAuthors)
    {
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
