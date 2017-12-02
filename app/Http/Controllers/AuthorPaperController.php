<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorPaperRequest;
use App\Http\Requests\UpdateAuthorPaperRequest;
use App\Repositories\AuthorPaperRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AuthorPaperController extends AppBaseController
{
    /** @var  AuthorPaperRepository */
    private $authorPaperRepository;

    public function __construct(AuthorPaperRepository $authorPaperRepo)
    {
        $this->authorPaperRepository = $authorPaperRepo;
    }

    /**
     * Display a listing of the AuthorPaper.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorPaperRepository->pushCriteria(new RequestCriteria($request));
        $authorPapers = $this->authorPaperRepository->with(['author', 'paper'])
            ->paginate(config('constants.DEFAULT_PAGINATION'));

        return view('author_papers.index')
            ->with('authorPapers', $authorPapers);
    }

    /**
     * Show the form for creating a new AuthorPaper.
     *
     * @return Response
     */
    public function create()
    {
        return view('author_papers.create');
    }

    /**
     * Store a newly created AuthorPaper in storage.
     *
     * @param CreateAuthorPaperRequest $request
     *
     * @return Response
     */
    public function store(CreateAuthorPaperRequest $request)
    {
        $input = $request->all();

        $authorPaper = $this->authorPaperRepository->create($input);

        Flash::success('Author Paper saved successfully.');

        return redirect(route('authorPapers.index'));
    }

    /**
     * Display the specified AuthorPaper.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $authorPaper = $this->authorPaperRepository->findWithoutFail($id);

        if (empty($authorPaper)) {
            Flash::error('Author Paper not found');

            return redirect(route('authorPapers.index'));
        }

        return view('author_papers.show')->with('authorPaper', $authorPaper);
    }

    /**
     * Show the form for editing the specified AuthorPaper.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $authorPaper = $this->authorPaperRepository->findWithoutFail($id);

        if (empty($authorPaper)) {
            Flash::error('Author Paper not found');

            return redirect(route('authorPapers.index'));
        }

        return view('author_papers.edit')->with('authorPaper', $authorPaper);
    }

    /**
     * Update the specified AuthorPaper in storage.
     *
     * @param  int              $id
     * @param UpdateAuthorPaperRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAuthorPaperRequest $request)
    {
        $authorPaper = $this->authorPaperRepository->findWithoutFail($id);

        if (empty($authorPaper)) {
            Flash::error('Author Paper not found');

            return redirect(route('authorPapers.index'));
        }

        $authorPaper = $this->authorPaperRepository->update($request->all(), $id);

        Flash::success('Author Paper updated successfully.');

        return redirect(route('authorPapers.index'));
    }

    /**
     * Remove the specified AuthorPaper from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $authorPaper = $this->authorPaperRepository->findWithoutFail($id);

        if (empty($authorPaper)) {
            Flash::error('Author Paper not found');

            return redirect(route('authorPapers.index'));
        }

        $this->authorPaperRepository->delete($id);

        Flash::success('Author Paper deleted successfully.');

        return redirect(route('authorPapers.index'));
    }
}
