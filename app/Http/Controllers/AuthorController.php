<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use Response;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Repositories\AuthorRepository;
use App\Http\Requests\UpdateAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class AuthorController extends AppBaseController
{
    /** @var  AuthorRepository */
    private $authorRepository;

    private $routeType = '';

    public function __construct(AuthorRepository $authorRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '':'user.';
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

    public function search(Request $request)
    {
        $query = trim($request->q);

        if (empty($query)) {
            Flash::error('Enter a keyword.');
            return redirect()->back();
        }

        $currentPage = $request->page;

        if (! is_numeric($currentPage)) {
            Flash::error('Invalid page.');
        }
        
        $perPage = config('constants.DEFAULT_PAGINATION');
//
//        if (! $request->session()->has('author_search_' . $query)) {
//            $authors = Author::search($request->q)->get();
//            session(['author_search_' . $query => $authors]);
//        } else {
//            $authors = $request->session()->get('author_search_' . $query);
//        }

//        $authors = $this->authorRepository->search($request->q)->get();

        $execution = "select *, match(authors.given_name, authors.surname) against ('{$query}') as s1,
	          match(universities.name) against ('{$query}') as s2 
              from authors inner join universities on authors.university_id = universities.id
              order by (s1 + s2 ) desc;";

        $authors = DB::select($execution);
        dd($authors);

        $authors = Author::search($query, [
            'given_name' => 5,
            'surname' => 5,
            'university.name' => 10
        ])
            ->paginate(15);

//        dd($authors);

        return view('authors.index', compact('authors'));
    }
}
