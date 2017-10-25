<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaperRequest;
use App\Http\Requests\UpdatePaperRequest;
use App\Repositories\PaperRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Cache;

class PaperController extends AppBaseController
{
    /** @var  PaperRepository */
    private $paperRepository;

    public function __construct(PaperRepository $paperRepo)
    {
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
        $papers = Cache::remember('papers.index',
            config('constants.CACHE_TIME'), function () {
                return $this->paperRepository
                    ->paginate(config('constants.DEFAULT_PAGINATION'));
            });

        return view('papers.index')
            ->with('papers', $papers);
    }

    /**
     * Show the form for creating a new Paper.
     *
     * @return Response
     */
    public function create()
    {
        return view('papers.create');
    }

    /**
     * Store a newly created Paper in storage.
     *
     * @param CreatePaperRequest $request
     *
     * @return Response
     */
    public function store(CreatePaperRequest $request)
    {
        $input = $request->all();

        $paper = $this->paperRepository->create($input);

        Flash::success('Paper saved successfully.');

        return redirect(route('papers.index'));
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

        return view('papers.show', compact('paper', 'keywords'));
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

    public function search(Request $request)
    {
        $papers = $this->paperRepository->search($request->q)->paginate(15);

        return view('papers.index', compact('papers'));
    }
}
