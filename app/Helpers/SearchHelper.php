<?php

namespace App\Helpers;

use DB;
use Illuminate\Http\Request;

class SearchHelper
{
    public static function searchingAuthorWithUniversity(Request $request, $currentPage, $offset, $perPage = 15)
    {
        $query = trim($request->q);

        if (! $request->session()->has('author_search_with_university_' . $query . '_' . strval($currentPage))) {
            $execution = "select authors.*, universities.name , match(authors.given_name, authors.surname) against ('{$query}') as s1,
                match(universities.name) against ('{$query}') as s2 
                from authors inner join universities on authors.university_id = universities.id
                where match(authors.given_name, authors.surname) against ('{$query}')
                or match(universities.name) against ('{$query}')
                order by (s1 + s2 ) desc limit {$perPage} offset {$offset}";

            $authors = DB::select($execution);

            session(['author_search_with_university_' . $query . '_' . strval($currentPage) => $authors]);
        } else {
            $authors = $request->session()->get('author_search_with_university_' . $query . '_' . strval($currentPage));
        }

        return $authors;
    }

    public static function searchingAuthor(Request $request, $currentPage, $offset, $perPage)
    {
        $query = trim($request->q);

        if (! $request->session()->has('author_search_' . $query . '_' . strval($currentPage))) {
            $execution = "select authors.* , match(authors.given_name, authors.surname) against ('{$query}') as s1,
                            from authors
                            where match(authors.given_name, authors.surname) against ('{$query}')
                            order by s1 desc limit {$perPage} offset {$offset}";
            $authors = DB::select($execution);

            session(['author_search_' . $query . '_' . strval($currentPage) => $authors]);
        } else {
            $authors = $request->session()->get('author_search_' . $query . '_' . strval($currentPage));
        }

        return $authors;
    }

//    public static function searchAuthorWithUniversity(SearchRequest $request, $routeType)
//    {
//        $query = trim($request->q);
//
//        $currentPage = intval($request->page);
//
//        if (empty($currentPage)) {
//            $currentPage = 1;
//        }
//
//        if (!is_numeric($currentPage) || $currentPage < 1) {
//            Flash::error('Invalid page.');
//            return view('authors.index')->with([
//                'routeType' => $routeType,
//            ]);
//        }
//
//        $perPage = config('constants.DEFAULT_PAGINATION');
//        $offset = $perPage * ($currentPage - 1);
//
//        if (!$request->session()->has('author_search_with_university_' . $query . '_' . strval($currentPage))) {
//            $execution = "select authors.*, universities.name , match(authors.given_name, authors.surname) against ('{$query}') as s1,
//                match(universities.name) against ('{$query}') as s2
//                from authors inner join universities on authors.university_id = universities.id
//                where match(authors.given_name, authors.surname) against ('{$query}')
//                or match(universities.name) against ('{$query}')
//                order by (s1 + s2 ) desc limit {$perPage} offset {$offset}";
//
//            $authors = DB::select($execution);
//
//            session(['author_search_with_university_' . $query => $authors]);
//        } else {
//            $authors = $request->session()->get('author_search_with_university_' . $query);
//        }
//
//        # Pagination
//        $url = route($routeType.'authors.search') . '?q=' . $query . '&page=';
//        $previousPage = $url . 1;
//        $nextPage = $url . ($currentPage + 1);
//
//        if ($currentPage > 1) {
//            $previousPage = $url . ($currentPage - 1);
//        }
//
//        # If empty result
//        if (count($authors) == 0) {
//            return view('authors.index')->with([
//                'authors' => $authors,
//                'routeType' => $routeType,
//                'nextPage' => $nextPage,
//                'previousPage' => $previousPage,
//            ]);
//        }
//
//        # View
//        $authors = json_decode(json_encode($authors), true);
//
//        for ($i = 0; $i < count($authors); $i++) {
//            $authors[$i]['university'] = [];
//            $authors[$i]['university']['name'] = $authors[$i]['name'];
//        }
//
//        return view('authors.index')->with([
//            'authors' => $authors,
//            'routeType' => $routeType,
//            'nextPage' => $nextPage,
//            'previousPage' => $previousPage,
//        ]);
//    }

//    public static function searchAuthor(SearchRequest $request, $routeType)
//    {
//        $query = trim($request->q);
//
//        $currentPage = intval($request->page);
//
//        if (empty($currentPage)) {
//            $currentPage = 1;
//        }
//
//        if (!is_numeric($currentPage) || $currentPage < 1) {
//            Flash::error('Invalid page.');
//            return view('authors.index')->with([
//                'routeType' => $routeType,
//            ]);
//        }
//
//        $perPage = config('constants.DEFAULT_PAGINATION');
//        $offset = $perPage * ($currentPage - 1);
//
//        if (!$request->session()->has('author_search_' . $query . '_' . strval($currentPage))) {
//            $execution = "select authors.* , match(authors.given_name, authors.surname) against ('{$query}') as s1,
//                from authors
//                where match(authors.given_name, authors.surname) against ('{$query}')
//                order by s1 desc limit {$perPage} offset {$offset}";
//            $authors = DB::select($execution);
//
//            session(['author_search_' . $query => $authors]);
//        } else {
//            $authors = $request->session()->get('author_search_' . $query);
//        }
//
//        # Pagination
//        $url = route($routeType.'authors.search') . '?q=' . $query . '&page=';
//        $previousPage = $url . 1;
//        $nextPage = $url . ($currentPage + 1);
//
//        if ($currentPage > 1) {
//            $previousPage = $url . ($currentPage - 1);
//        }
//
//        # If empty result
//        if (count($authors) == 0) {
//            return view('authors.index')->with([
//                'authors' => $authors,
//                'routeType' => $routeType,
//                'nextPage' => $nextPage,
//                'previousPage' => $previousPage,
//            ]);
//        }
//
//        # View
//        $authors = json_decode(json_encode($authors), true);
//
//        for ($i = 0; $i < count($authors); $i++) {
//            $authors[$i]['university'] = [];
//            $authors[$i]['university']['name'] = $authors[$i]['name'];
//        }
//
//        return view('authors.index')->with([
//            'authors' => $authors,
//            'routeType' => $routeType,
//            'nextPage' => $nextPage,
//            'previousPage' => $previousPage,
//        ]);
//    }

//    public static function searchPaper(SearchRequest $request, $routeType)
//    {
//        $query = trim($request->q);
//
//        $currentPage = intval($request->page);
//
//        if (empty($currentPage)) {
//            $currentPage = 1;
//        }
//
//        if (!is_numeric($currentPage) || $currentPage < 1) {
//            Flash::error('Invalid page.');
//            return view('papers.index')->with([
//                'routeType' => $routeType,
//            ]);
//        }
//
//        $perPage = config('constants.DEFAULT_PAGINATION');
//        $offset = $perPage * ($currentPage - 1);
//
//        if (!$request->session()->has('paper_search_' . $query . '_' . strval($currentPage))) {
//            $execution = "select *, match(papers.title) against ('{$query}') as s1 from papers
//                          where
//                            match(papers.title) against ('{$query}')
//                            or papers.id = '{$query}'
//                            or papers.issn = '{$query}'
//                          order by s1 desc limit {$perPage} offset {$offset};";
//
//            $papers = DB::select($execution);
//
//            session(['author_search_' . $query => $papers]);
//        } else {
//            $papers = $request->session()->get('paper_search_' . $query);
//        }
//
//        # Pagination
//        $url = route($routeType.'papers.search') . '?q=' . $query . '&page=';
//        $previousPage = $url . 1;
//        $nextPage = $url . ($currentPage + 1);
//
//        if ($currentPage > 1) {
//            $previousPage = $url . ($currentPage - 1);
//        }
//
//        if (count($papers) == 0) {
//            return view('papers.index')->with([
//                'papers' => $papers,
//                'routeType' => $routeType,
//                'nextPage' => $nextPage,
//                'previousPage' => $previousPage,
//            ]);
//        }
//
//        $papers = json_decode(json_encode($papers), true);
//
//        return view('papers.index')->with([
//            'papers' => $papers,
//            'routeType' => $routeType,
//            'nextPage' => $nextPage,
//            'previousPage' => $previousPage,
//        ]);
//
//    }
}
