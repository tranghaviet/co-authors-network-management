<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Author;
use App\Models\AuthorPaper;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\AuthorPaperRepository;
use App\Http\Requests\UpdateAuthorPaperRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class AuthorPaperController extends AppBaseController
{
    /** @var  AuthorPaperRepository */
    private $authorPaperRepository;

    private $routeType;

    public function __construct(AuthorPaperRepository $authorPaperRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '' : 'user.';
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
            ->with([
                'authorPapers' => $authorPapers,
                'routeType' => $this->routeType,
            ]);
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

    public function search(SearchRequest $request)
    {
    }
}
