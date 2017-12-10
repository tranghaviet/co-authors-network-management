<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use Response;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\AuthorRepository;
use App\Http\Requests\UpdateAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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


        $authors = $authors->toArray()['data'];
        extract(get_object_vars($this));

        return view('authors.index', compact('authors', 'paginator', 'routeType'));
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
//        return SearchHelper::searchAuthorWithUniversity($request, $this->routeType);

        $query = trim($request->q);

        $currentPage = intval($request->page);

        if (empty($currentPage)) {
            $currentPage = 1;
        }


        if (!is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');
            return view('authors.index')->with([
                'routeType' => $this->routeType,
            ]);
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

            try {
                $authors = DB::select($execution);
            } catch (\Exception $e) {
                \Flash::error('Index in progress.. Come back later.');
                // \Artisan::call('author:re-index', ['--university' => true]);
                $process = new Process('php ../artisan author:re-index --university');
                $process->start();
                return redirect()->back();                    
            }    

            session(['author_search_' . $query => $authors]);
        } else {
            $authors = $request->session()->get('author_search_' . $query);
        }


        # Pagination
        $url = route($this->routeType.'authors.search') . '?q=' . $query . '&page=';
        $previousPage = $url . 1;
        $nextPage = $url . ($currentPage + 1);

        if ($currentPage > 1) {
            $previousPage = $url . ($currentPage - 1);
        }

        # If empty result
        if (count($authors) == 0) {
            return view('authors.index')->with([
                'authors' => $authors,
                'routeType' => $this->routeType,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
            ]);
        }

        # View
        $authors = json_decode(json_encode($authors), true);
        for ($i = 0; $i < count($authors); $i++) {
            $authors[$i]['university'] = [];
            $authors[$i]['university']['name'] = $authors[$i]['name'];
        }

        return view('authors.index')->with([
            'authors' => $authors,
            'routeType' => $this->routeType,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage,
        ]);
    }
}
