<?php

namespace App\Helpers;

use DB;
use Cache;
use Illuminate\Http\Request;

class SearchHelper
{
    public static function searchingAuthorWithUniversity(Request $request, $currentPage, $offset, $perPage = 15)
    {
        $query = trim($request->q);

        if (! Cache::has('author_search_with_university_' . $query . '_' . strval($currentPage))) {
            $execution = "SELECT authors.*, universities.name,
                    MATCH(authors.given_name) against (?) AS s1,
                    MATCH(authors.surname) against (?) AS s2,
                    MATCH(universities.name) against (?) AS s3
                    FROM authors INNER JOIN universities ON authors.university_id = universities.id
                    WHERE
                    MATCH(authors.given_name) AGAINST (?)
                    OR MATCH(authors.surname) AGAINST (?)
                    OR MATCH(universities.name) AGAINST (?)
                    ORDER BY (s1 + s2 + s3) DESC
                    LIMIT {$perPage} OFFSET {$offset};";

            $authors = DB::select($execution, array_fill(0, 6, $query));

            Cache::put('author_search_with_university_' . $query . '_' . strval($currentPage), $authors, 10);
        } else {
            $authors = Cache::get('author_search_with_university_' . $query . '_' . strval($currentPage));
        }

        return $authors;
    }

    public static function searchPapers(Request $request, $currentPage, $offset, $perPage)
    {
        $query = trim($request->q);

        if (! Cache::has('paper_search_' . $query . '_' . strval($currentPage))) {

            $execution = "SELECT *, MATCH(papers.title) AGAINST (':query') AS s1
                        FROM papers
                        WHERE
                        MATCH(papers.title) AGAINST (':query')
                        OR papers.id = ':query'
                        OR papers.issn = ':query'
                        ORDER BY s1 DESC LIMIT {$perPage} OFFSET {$offset};";

            $papers = DB::select($execution, ['query' => $query]);

            Cache::put('paper_search_' . $query . '_' . strval($currentPage), $papers, 10);
        } else {
            $papers = Cache::get('paper_search_' . $query . '_' . strval($currentPage));
        }

        return $papers;
    }

    public static function searchingAuthor(Request $request, $currentPage, $offset, $perPage)
    {
        $query = trim($request->q);

        if (! $request->session()->has('author_search_' . $query . '_' . strval($currentPage))) {
            $execution = "select authors.* , match(authors.given_name, authors.surname) against ('{$query}' IN NATURAL LANGUAGE MODE) as s1,
                            from authors
                            where match(authors.given_name, authors.surname) against ('{$query}' IN NATURAL LANGUAGE MODE)
                            or authors.given_name like '{$query}%'
                            or authors.surname like '{$query}%'
                            order by s1 desc limit {$perPage} offset {$offset}";
            $authors = DB::select($execution);

            session(['author_search_' . $query . '_' . strval($currentPage) => $authors]);
        } else {
            $authors = $request->session()->get('author_search_' . $query . '_' . strval($currentPage));
        }

        return $authors;
    }
}
