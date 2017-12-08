<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\PaperRepository;
use App\Http\Requests\UpdatePaperRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class PaperController extends AppBaseController
{
    /** @var  PaperRepository */
    private $paperRepository;

    private $routeType = '';

    public function __construct(PaperRepository $paperRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '':'user.';

        $this->paperRepository = $paperRepo;
    }

    /**
     * Display a listing of the Paper.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->paperRepository->pushCriteria(new RequestCriteria($request));
        $papers = $this->paperRepository->paginate(config('constants.DEFAULT_PAGINATION'));

        $paginator = $papers->render();
        $papers = $papers->toArray()['data'];
        extract(get_object_vars($this));

        return view('papers.index', compact('papers', 'paginator', 'routeType'));
    }

    /**
     * Display the specified Paper.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paper = $this->paperRepository->findWithoutFail($id);

        if (empty($paper)) {
            Flash::error('Paper not found');

            return redirect(route('papers.index'));
        }
        $keywords = $paper->keywords->map(function ($keyword) {
            return $keyword['content'];
        });
        $keywords = implode(', ', $keywords->toArray());

        extract(get_object_vars($this));

        return view('papers.show', compact('paper', 'keywords', 'routeType'));
    }

    /**
     * Show the form for editing the specified Paper.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paper = $this->paperRepository->findWithoutFail($id);

        if (empty($paper)) {
            Flash::error('Paper not found');

            return redirect(route('papers.index'));
        }

        return view('papers.edit')->with('paper', $paper);
    }

    /**
     * Update the specified Paper in storage.
     *
     * @param  int              $id
     * @param UpdatePaperRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaperRequest $request)
    {
        $paper = $this->paperRepository->findWithoutFail($id);

        if (empty($paper)) {
            Flash::error('Paper not found');

            return redirect(route('papers.index'));
        }

        $paper = $this->paperRepository->update($request->all(), $id);

        Flash::success('Paper updated successfully.');

        return redirect(route('papers.index'));
    }

    /**
     * Remove the specified Paper from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paper = $this->paperRepository->findWithoutFail($id);

        if (empty($paper)) {
            Flash::error('Paper not found');

            return redirect(route('papers.index'));
        }

        $this->paperRepository->delete($id);

        Flash::success('Paper deleted successfully.');

        return redirect(route('papers.index'));
    }

    public function search(SearchRequest $request)
    {
        $query = trim($request->q);

        if (empty($query)) {
            Flash::error('Enter a keyword.');
            return redirect()->back();
        }

        $papers = $this->paperRepository->search($query)->paginate(15);

        return view('papers.index', compact('papers'));
    }
}
