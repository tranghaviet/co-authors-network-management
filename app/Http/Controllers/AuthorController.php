<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\AuthorRepository;
use App\Http\Requests\UpdateAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\Process\Process as Process;

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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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

        try {
            $author = $this->authorRepository->update($request->all(), $id);
            Flash::success('Author updated successfully.');
        } catch (\Exception $e) {
            Flash::error('Some fileds not valid');
        }

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

        if (! is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');

            return view('authors.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        try {
            $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
        } catch (\Exception $e) {
            try {
                \Artisan::call('author:re-index', ['--university' => true]);
                $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
            } catch (\Exception $e) {
                \Flash::error('Index in progress...try after few seconds');
                \Log::debug('author:re-index fail', $e->getTrace());

                $process = new Process('php ../artisan author:re-index --university');
                $process->start();

                return redirect()->back();
            }
        }

        $authors = json_decode(json_encode($authors), true);
        $totalResults = count($authors);

        for ($i = 0; $i < $totalResults; $i++) {
            $authors[$i]['university'] = [];
            $authors[$i]['university']['name'] = $authors[$i]['name'];
        }

        // Pagination
        $data = [
            'authors' => $authors,
            'routeType' => $this->routeType,
        ];

        // If empty result
        if ($totalResults == 0) {
            return view('authors.index')->with($data);
        }

        $url = route($this->routeType.'authors.search') . '?q=' . $query . '&page=';

        if ($currentPage > 1) { // at page 2,3...
            $previousPage = $url . ($currentPage - 1);

            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);

                return view('authors.index')->with(array_merge($data, compact('previousPage', 'nextPage')));
            }

            return view('authors.index')->with(array_merge($data, compact('previousPage')));
        } else { // at page 1
            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);
            }

            return view('authors.index')->with(array_merge($data, compact('nextPage')));
        }
    }
}
