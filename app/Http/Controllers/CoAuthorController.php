<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\CoAuthor;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\CoAuthorRepository;
use App\Http\Requests\UpdateCoAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class CoAuthorController extends AppBaseController
{
    /** @var  CoAuthorRepository */
    private $coAuthorRepository;
    private $routeType;

    public function __construct(CoAuthorRepository $coAuthorRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '' : 'user.';
        $this->coAuthorRepository = $coAuthorRepo;
    }

    /**
     * Display a listing of the CoAuthor.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $this->coAuthorRepository->pushCriteria(new RequestCriteria($request));

        $coAuthors = $this->coAuthorRepository
            ->with('firstAuthor.university')
            ->with('secondAuthor.university')
            ->paginate(config('constants.DEFAULT_PAGINATION'));

        $paginator = $coAuthors->render();

        $coAuthors = $coAuthors->toArray()['data'];

        return view('co_authors.index', array_merge(compact('coAuthors', 'paginator'), ['routeType' => $this->routeType]));
    }

    /**
     * Show the form for creating a new CoAuthor.
     *
     * @return Response
     */
    public function create()
    {
        return view('co_authors.create');
    }

    /**
     * Store a newly created CoAuthor in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $coAuthor = $this->coAuthorRepository->create($input);

        Flash::success('Co Author saved successfully.');

        return redirect(route('coAuthors.index'));
    }

    /**
     * Display the specified CoAuthor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $coAuthor = $this->coAuthorRepository->findWithoutFail($id);

        if (empty($coAuthor)) {
            Flash::error('Co Author not found');

            return redirect(route('coAuthors.index'));
        }

        return view('co_authors.show')->with('coAuthor', $coAuthor);
    }

    /**
     * Show the form for editing the specified CoAuthor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $coAuthor = $this->coAuthorRepository->findWithoutFail($id);

        if (empty($coAuthor)) {
            Flash::error('Co Author not found');

            return redirect(route('coAuthors.index'));
        }

        return view('co_authors.edit')->with('coAuthor', $coAuthor);
    }

    /**
     * Update the specified CoAuthor in storage.
     *
     * @param  int $id
     * @param UpdateCoAuthorRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCoAuthorRequest $request)
    {
        $coAuthor = $this->coAuthorRepository->findWithoutFail($id);

        if (empty($coAuthor)) {
            Flash::error('Co Author not found');

            return redirect(route('coAuthors.index'));
        }

        $coAuthor = $this->coAuthorRepository->update($request->all(), $id);

        Flash::success('Co Author updated successfully.');

        return redirect(route('coAuthors.index'));
    }

    /**
     * Remove the specified CoAuthor from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $coAuthor = $this->coAuthorRepository->findWithoutFail($id);

        if (empty($coAuthor)) {
            Flash::error('Co Author not found');

            return redirect(route('coAuthors.index'));
        }

        $this->coAuthorRepository->delete($id);

        Flash::success('Co Author deleted successfully.');

        return redirect(route('coAuthors.index'));
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
            return view('co_authors.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);

        # Pagination
        $url = route($this->routeType.'co_authors.search') . '?q=' . $query . '&page=';
        $previousPage = $url . 1;
        $nextPage = $url . ($currentPage + 1);

        if ($currentPage > 1) {
            $previousPage = $url . ($currentPage - 1);
        }

        # If empty result
        if (count($authors) == 0) {
            return view('co_authors.index')->with([
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

            $authors[$authors[$i]['id']] = $authors[$i];

            unset($authors[$i]);
        }

        $authorIds = array_keys($authors);

        $coAuthors = CoAuthor::whereIn('first_author_id', $authorIds)
            ->orWhereIn('second_author_id', $authorIds)->get()->toArray();

        for ($i = 0; $i < count($coAuthors); $i++) {
            $coAuthors[$i]['first_author'] = $authors[ $coAuthors[$i]['first_author_id']];
            $coAuthors[$i]['second_author'] = $authors[ $coAuthors[$i]['second_author_id']];
        }

        return view('co_authors.index')->with([
            'coAuthors' => $coAuthors,
            'routeType' => $this->routeType,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage,
        ]);
    }
}
