<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Illuminate\Http\Request;
use App\Repositories\CoAuthorRepository;
use App\Http\Requests\UpdateCoAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class CoAuthorController extends AppBaseController
{
    /** @var  CoAuthorRepository */
    private $coAuthorRepository;

    public function __construct(CoAuthorRepository $coAuthorRepo)
    {
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
        $arr = [1,2,3,4,5,6];
//        $a = new \Illuminate\Pagination\Paginator($arr, 3, 2);
        $a = new \Illuminate\Pagination\LengthAwarePaginator($arr, count($arr), 2, 2);
        dump($a);

        $this->coAuthorRepository->pushCriteria(new RequestCriteria($request));

        $coAuthors = $this->coAuthorRepository
            ->with('firstAuthor.university')
            ->with('secondAuthor.university')
//            ->all();
            ->paginate(config('constants.DEFAULT_PAGINATION'));
        $paginator = $coAuthors->render();
        dump($coAuthors);

        $coAuthors = $coAuthors->toArray()['data'];

//        dd($coAuthors);
        return view('co_authors.index', compact('coAuthors', 'paginator'));
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

    public function search(Request $request)
    {
        $coAuthors = $this->coAuthorRepository->search($request->q)->paginate(config('constants.DEFAULT_PAGINATION', 15));

        return view('co_authors.index', compact('coAuthors'));
    }
}
