<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\CandidateRepository;
use App\Models\CoAuthor;
use App\Models\Author;
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
        $score1 = intval($request->score_1);
        $score2 = intval($request->score_2);
        $score3 = intval($request->score_3);
        $query = trim($request->q);

        if (is_null($score1) || is_nan($score1))  {
            $score1 = 0;
        }
        if (is_null($score2) || is_nan($score2))  {
            $score2 = 0;
        }if (is_null($score3) || is_nan($score3))  {
            $score3 = 0;
        }

        $currentPage = intval($request->page);

        if (empty($currentPage)) {
            $currentPage = 1;
        }


        if (!is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');
            return view('candidates.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        try {
            $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
        } catch (\Exception $e) {
            \Flash::error('Index in progress.. Come back later.');
            \Artisan::call('author:re-index', ['--university' => true]);
            return redirect()->back();                    
        }  


        # Pagination
        $url = route($this->routeType.'candidates.search') . '?q=' . $query . '&page=';
        $previousPage = $url . 1;
        $nextPage = $url . ($currentPage + 1);

        if ($currentPage > 1) {
            $previousPage = $url . ($currentPage - 1);
        }

        # If empty result
        if (count($authors) == 0) {
            return view('candidates.index')->with([
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

        # Find authors by query
        $authorIds = array_keys($authors);

        # Find coauthors by first author id
        $coAuthors1 = CoAuthor::whereIn('first_author_id', $authorIds)->with('candidate')
            ->get()->toArray();
        $secondAuthorIds = array_map(function($x) { return intval($x['second_author_id']); }, $coAuthors1);
        $secondAuthors = Author::whereIn('id', $secondAuthorIds)->with('university')->get()->toArray();
        for ($i = 0; $i < count($secondAuthors); $i++) {
            $secondAuthors[$secondAuthors[$i]['id']] = $secondAuthors[$i];
            unset($secondAuthors[$i]);
        }
        

        # Find coauthors by first author id
        $coAuthors2 = CoAuthor::whereIn('second_author_id', $authorIds)->with('candidate')
            ->get()->toArray();
        // dump($coAuthors2);
        $firstAuthorIds = array_map(function($x) { return intval($x['first_author_id']); }, $coAuthors2);
        $firstAuthors = Author::whereIn('id', $firstAuthorIds)->with('university')->get()->toArray();
        for ($i = 0; $i < count($firstAuthors); $i++) {
            $firstAuthors[$firstAuthors[$i]['id']] = $firstAuthors[$i];
            unset($firstAuthors[$i]);
        }

        
        # Combine co authors
        for ($i = 0; $i < count($coAuthors1); $i++) {
            $coAuthors1[$i]['first_author'] = $authors[$coAuthors1[$i]['first_author_id']];
            $coAuthors1[$i]['second_author'] = $secondAuthors[$secondAuthorIds[$i]];
        }

        for ($i = 0; $i < count($coAuthors2); $i++) {
            $coAuthors2[$i]['first_author'] = $firstAuthors[$firstAuthorIds[$i]];
            $coAuthors2[$i]['second_author'] = $authors[$coAuthors2[$i]['second_author_id']];
        }

        $coAuthors = array_merge($coAuthors1, $coAuthors2);
        $coAuthors = array_map(function($x) { 
            $x['score_1'] = $x['candidate']['score_1']; 
            $x['score_2'] = $x['candidate']['score_2']; 
            $x['score_3'] = $x['candidate']['score_3']; 
            unset($x['candidate']);
            return $x;
        }, $coAuthors);

        usort($coAuthors, function($a, $b) {
            return $b['score_1'] - $a['score_1'];
        });

        $result = [];
        $count = 0;
        foreach ($coAuthors as $key => $value) {
            if ($value['score_1'] >= $score1 && $value['score_2'] >= $score2 && 
                        $value['score_3'] >= $score3) {
                array_push($result, $value);
                $count ++;
                if ($count > 15) {
                    break;
                }
            }
        }

        # Return view
        return view('candidates.search')->with([
            'candidates' => $result,
            'routeType' => $this->routeType,
        ]);
    }
}
