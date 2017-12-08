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
use Illuminate\Pagination\LengthAwarePaginator;

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

        $currentPage = intval($request->page);

        if (! is_numeric($currentPage)) {
            Flash::error('Invalid page.');
        }
        
        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * $currentPage;

        dump($offset);
        dump($perPage);
//
//        if (! $request->session()->has('author_search_' . $query)) {
//            $authors = Author::search($request->q)->get();
//            session(['author_search_' . $query => $authors]);
//        } else {
//            $authors = $request->session()->get('author_search_' . $query);
//        }

//        $authors = $this->authorRepository->search($request->q)->get();

        $execution = "select authors.*, universities.name , match(authors.given_name, authors.surname) against ('{$query}') as s1,
                match(universities.name) against ('{$query}') as s2 
                from authors inner join universities on authors.university_id = universities.id
                order by (s1 + s2 ) desc limit {$perPage} offset {$offset}";

        $authors = DB::select($execution);
        $authors = json_decode(json_encode($authors), true);

        for ($i=0; $i < count($authors); $i++) { 
            $authors[$i]['university'] = [];
            $authors[$i]['university']['name'] = $authors[$i]['name'];
        }

        // dump(json_decode(json_encode($authors), true));
        dump($authors);

        $paginator = new LengthAwarePaginator($authors, count($authors), $perPage, $currentPage);
        dump($paginator);
        dump($paginator->render(view('vendor/pagination/bootstrap-4')));

       dd();

        return view('authors.index')->with([
            'authors' => json_decode(json_encode($authors), true),
            'routeType' => $this->routeType,
        ]);
    }
}
