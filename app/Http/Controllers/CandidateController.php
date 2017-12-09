<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\CandidateRepository;
use App\Http\Requests\UpdateCandidateRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class CandidateController extends AppBaseController
{
    /** @var  CandidateRepository */
    private $candidateRepository;

    private $routeType;

    public function __construct(CandidateRepository $candidateRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '' : 'user.';
        $this->candidateRepository = $candidateRepo;
    }

    /**
     * Display a listing of the Candidate.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->candidateRepository->pushCriteria(new RequestCriteria($request));
        $candidates = $this->candidateRepository
            ->with('coAuthor.firstAuthor')
            ->with('coAuthor.secondAuthor')
            ->paginate(config('constants.DEFAULT_PAGINATION'));

        if ($candidates->count() == 0) {
            return view('candidates.index')
                ->with([
                    'routeType' => $this->routeType,
                ]);
        }
        $paginator = $candidates->render();
        $candidates = $candidates->toArray()['data'];

        return view('candidates.index')
            ->with([
                'candidates' => $candidates,
                'paginator' => $paginator,
                'routeType' => $this->routeType,
            ]);
    }

    /**
     * Display the specified Candidate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $candidate = $this->candidateRepository->findWithoutFail($id);

        if (empty($candidate)) {
            Flash::error('Candidate not found');

            return redirect(route('candidates.index'));
        }

        return view('candidates.show')->with('candidate', $candidate);
    }

    /**
     * Show the form for editing the specified Candidate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $candidate = $this->candidateRepository->findWithoutFail($id);

        if (empty($candidate)) {
            Flash::error('Candidate not found');

            return redirect(route('candidates.index'));
        }

        return view('candidates.edit')->with('candidate', $candidate);
    }

    /**
     * Update the specified Candidate in storage.
     *
     * @param  int $id
     * @param UpdateCandidateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCandidateRequest $request)
    {
        $candidate = $this->candidateRepository->findWithoutFail($id);

        if (empty($candidate)) {
            Flash::error('Candidate not found');

            return redirect(route('candidates.index'));
        }

        $candidate = $this->candidateRepository->update($request->all(), $id);

        Flash::success('Candidate updated successfully.');

        return redirect(route('candidates.index'));
    }

    /**
     * Remove the specified Candidate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $candidate = $this->candidateRepository->findWithoutFail($id);

        if (empty($candidate)) {
            Flash::error('Candidate not found');

            return redirect(route('candidates.index'));
        }

        $this->candidateRepository->delete($id);

        Flash::success('Candidate deleted successfully.');

        return redirect(route('candidates.index'));
    }

    public function search(Request $request)
    {
        $score1 = $request->score_1;
        $score2 = $request->score_2;
        $score3 = $request->score_3;

        if (is_null($score1) || is_nan($score1))  {
            $score1 = 0;
        }

        $candidates = Candidate::where('score_1', '>=', $score1)->get();

        if ($candidates->count() == 0) {
            return view('candidates.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $authors = SearchHelper::searchingAuthorWithUniversity($request, 1, 0, 15);

        dd();

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

        if (isset($input['q'])) {
            $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
        }

        $candidatesByOtherCriteria = null;

        // if user search on at least one criteria
        if ($criteria != null) {
            $candidatesByOtherCriteria = Candidate::where($criteria)->get();

//            if (! $candidatesByName->isEmpty()) {
//                $candidateIdsByName = $candidatesByName->map(function ($candidate) {
//                    return $candidate['id'];
//                });
//
//                $candidatesByOtherCriteria = $candidatesByOtherCriteria->map(function ($candidate) use ($candidateIdsByName) {
//                    if (in_array($candidate['id'], $candidateIdsByName->toArray())) {
//                        return $candidate;
//                    }
//                });
//            }

            return view('candidates.index')->with([
                'candidates' => $candidatesByOtherCriteria,
                'isPaginated' => false,
            ]);
        }

        return view('candidates.index')->with('candidates', $candidatesByName);
    }
}
