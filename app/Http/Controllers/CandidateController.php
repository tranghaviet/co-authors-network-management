<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\CandidateRepository;
use App\Http\Requests\UpdateCandidateRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class CandidateController extends AppBaseController
{
    /** @var  CandidateRepository */
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepo)
    {
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

        return view('candidates.index')
            ->with([
                'candidates' => $candidates,
                'isPaginated' => true,
            ]);
    }

    /**
     * Show the form for creating a new Candidate.
     *
     * @return Response
     */
    public function create()
    {
        return view('candidates.create');
    }

    /**
     * Store a newly created Candidate in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $candidate = $this->candidateRepository->create($input);

        Flash::success('Candidate saved successfully.');

        return redirect(route('candidates.index'));
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

    public function search(SearchRequest $request)
    {
        $input = $request->all();

        $candidatesByName = $this->candidateRepository->search($request->q)->get();

        $criteria = null;
        unset($input['q']);

        foreach ($input as $key => $value) {
            if ($value != null) {
                $criteria[$key] = $value;
            }
        }

        $candidatesByOtherCriteria = null;

        // if user search on at least one criteria
        if ($criteria != null) {
            $candidatesByOtherCriteria = Candidate::where($criteria)->get();

            if (! $candidatesByName->isEmpty()) {
                $candidateIdsByName = $candidatesByName->map(function ($candidate) {
                    return $candidate['id'];
                });

                $candidatesByOtherCriteria = $candidatesByOtherCriteria->map(function ($candidate) use ($candidateIdsByName) {
                    if (in_array($candidate['id'], $candidateIdsByName->toArray())) {
                        return $candidate;
                    }
                });
            }

            return view('candidates.index')->with([
                'candidates' => $candidatesByOtherCriteria,
                'isPaginated' => false,
            ]);
        }

        return view('candidates.index')->with('candidates', $candidatesByName);
    }
}
