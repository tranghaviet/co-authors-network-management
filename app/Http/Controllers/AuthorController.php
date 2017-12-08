<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use Response;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\AuthorRepository;
use App\Http\Requests\UpdateAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorController extends AppBaseController
{
    /** @var  AuthorRepository */
    private $authorRepository;

    private $routeType = '';

    public function __construct(AuthorRepository $authorRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '' : 'user.';
        $this->authorRepository = $authorRepo;
    }

    /**
     * Display a listing of the Author.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorRepository->pushCriteria(new RequestCriteria($request));

        $authors = $this->authorRepository->with('university')
            ->paginate(config('constants.DEFAULT_PAGINATION'));
        $paginator = $authors->render();
        dump($authors);
        $authors = $authors->toArray()['data'];
        extract(get_object_vars($this));

        return view('authors.index', compact('authors', 'paginator', 'routeType'));
    }

    /**
     * Show the form for creating a new Author.
     *
     * @return Response
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created Author in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        dd($input);

        $author = $this->authorRepository->create($input);

        Flash::success('Author saved successfully.');

        return redirect(route('authors.index'));
    }

    /**
     * Display the specified Author.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $author = $this->authorRepository->findWithoutFail($id);

        if (empty($author)) {
            Flash::error('Author not found');

            return redirect(route('authors.index'));
        }

        $subjects = $author->subjects->map(function ($subject) {
            return $subject['name'];
        });
        $subjects = implode(', ', $subjects->toArray());

        $papers = $author->papers()->get(['id', 'title']);
        // TODO: find $coAuthor, $topCandidates
        $collaborators = $author->collaborators($papers, ['id', 'given_name', 'surname']);

        extract(get_object_vars($this));

        return view('authors.show', compact('author', 'subjects', 'papers', 'collaborators', 'routeType'));
    }

    /**
     * Show the form for editing the specified Author.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $author = $this->authorRepository->findWithoutFail($id);

        if (empty($author)) {
            Flash::error('Author not found');

            return redirect(route('authors.index'));
        }

        return view('authors.edit')->with('author', $author);
    }

    /**
     * Update the specified Author in storage.
     *
     * @param  int $id
     * @param UpdateAuthorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAuthorRequest $request)
    {
        $author = $this->authorRepository->findWithoutFail($id);

        if (empty($author)) {
            Flash::error('Author not found');

            return redirect(route('authors.index'));
        }

        $author = $this->authorRepository->update($request->all(), $id);

        Flash::success('Author updated successfully.');

        return redirect(route('authors.index'));
    }

    /**
     * Remove the specified Author from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $author = $this->authorRepository->findWithoutFail($id);

        if (empty($author)) {
            Flash::error('Author not found');

            return redirect(route('authors.index'));
        }

        $this->authorRepository->delete($id);

        Flash::success('Author deleted successfully.');

        return redirect(route('authors.index'));
    }

    public function search(SearchRequest $request)
    {
        $query = trim($request->q);

        $currentPage = intval($request->page);

        if (empty($currentPage)) {
            $currentPage = 1;
        }

        if (!is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        if (!$request->session()->has('author_search_' . $query . '_' . strval($currentPage))) {
            $execution = "select authors.*, universities.name , match(authors.given_name, authors.surname) against ('{$query}') as s1,
                match(universities.name) against ('{$query}') as s2 
                from authors inner join universities on authors.university_id = universities.id
                where match(authors.given_name, authors.surname) against ('{$query}')
                or match(universities.name) against ('{$query}')
                order by (s1 + s2 ) desc limit {$perPage} offset {$offset}";
            $authors = DB::select($execution);

            session(['author_search_' . $query => $authors]);
        } else {
            $authors = $request->session()->get('author_search_' . $query);
        }

        $authors = json_decode(json_encode($authors), true);

        for ($i = 0; $i < count($authors); $i++) {
            $authors[$i]['university'] = [];
            $authors[$i]['university']['name'] = $authors[$i]['name'];
        }

        $itemsForCurrentPage = array_slice($authors, $offset, $perPage, true);
        $result = new LengthAwarePaginator($authors, 100000, $perPage, $currentPage);
        $result->setPath('/authors/search?q=' . $query);
        $paginator = $result->render();

        return view('authors.index')->with([
            'authors' => $authors,
            'routeType' => $this->routeType,
            'paginator' => $paginator,
        ]);
    }
}
