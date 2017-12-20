<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\CoAuthor;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Repositories\CoAuthorRepository;
use App\Http\Requests\UpdateCoAuthorRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\Process\Process as Process;

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

    public function search(Request $request)
    {
        $query = trim($request->q);

        $jointPapers = intval($request->no_of_joint_papers);
        if (! $jointPapers || is_nan($jointPapers)) {
            $jointPapers = 0;
        }

        $jointAuthors = intval($request->no_of_mutual_authors);
        if (! $jointAuthors || is_nan($jointAuthors)) {
            $jointAuthors = 0;
        }

        $currentPage = intval($request->page);

        if (empty($currentPage)) {
            $currentPage = 1;
        }

        if (! is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');

            return view('co_authors.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        // Pagination
        $url = route($this->routeType.'coAuthors.search') . '?q=' . $query
            . '&no_of_joint_papers=' . $jointPapers
            . '&no_of_mutual_authors=' . $jointAuthors
            . '&page=';

        if (! empty($query)) {
            try {
                $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
            } catch (\Exception $e) {
                try {
                    \Artisan::call('author:re-index', ['--university' => true]);
                    $authors = SearchHelper::searchingAuthorWithUniversity($request, $currentPage, $offset, $perPage);
                } catch (\Exception $e) {
                    \Log::debug('author:re-index fail', $e->getTrace());
                    \Flash::error('Index in progress...try after few seconds');
                    $process = new Process('php ../artisan author:re-index --university');
                    $process->start();

                    return redirect()->back();
                }
            }

            // If empty result
            if (count($authors) == 0) {
                return view('co_authors.index')->with([
                    'routeType' => $this->routeType,
                ]);
            }

            // View
            $authors = json_decode(json_encode($authors), true);

            for ($i = 0; $i < count($authors); $i++) {
                $authors[$i]['university'] = [];
                $authors[$i]['university']['name'] = $authors[$i]['name'];
                $authors[$authors[$i]['id']] = $authors[$i];

                unset($authors[$i]);
            }
            // Find authors by query
            $authorIds = array_keys($authors);

            // Find coauthors by first author id
            $coAuthors1 = CoAuthor::whereIn('first_author_id', $authorIds)
                ->where('no_of_joint_papers', '>=', $jointPapers)
                ->where('no_of_mutual_authors', '>=', $jointAuthors)
                ->get()->toArray();

            $secondAuthorIds = array_map(function ($x) {
                return intval($x['second_author_id']);
            }, $coAuthors1);

            $secondAuthors = Author::whereIn('id', $secondAuthorIds)->with('university')->get()->toArray();

            for ($i = 0; $i < count($secondAuthors); $i++) {
                $secondAuthors[$secondAuthors[$i]['id']] = $secondAuthors[$i];
                unset($secondAuthors[$i]);
            }

            // Find coauthors by second author id
            $coAuthors2 = CoAuthor::whereIn('second_author_id', $authorIds)
                ->where('no_of_joint_papers', '>=', $jointPapers)
                ->where('no_of_mutual_authors', '>=', $jointAuthors)
                ->get()->toArray();
            // dump($coAuthors2);
            $firstAuthorIds = array_map(function ($x) {
                return intval($x['first_author_id']);
            }, $coAuthors2);
            $firstAuthors = Author::whereIn('id', $firstAuthorIds)->with('university')->get()->toArray();
            for ($i = 0; $i < count($firstAuthors); $i++) {
                $firstAuthors[$firstAuthors[$i]['id']] = $firstAuthors[$i];
                unset($firstAuthors[$i]);
            }
        } else {
            // Find coauthors by first author id
            $coAuthors1 = CoAuthor::where('no_of_joint_papers', '>=', $jointPapers)
                ->where('no_of_mutual_authors', '>=', $jointAuthors)
                ->offset($offset)->limit($perPage)
                ->with('firstAuthor.university')
                ->with('secondAuthor.university')
                ->get()->toArray();
            $coAuthors2 = [];
        }

        $coAuthors = array_merge($coAuthors1, $coAuthors2);

        $data = [
            'coAuthors' => $coAuthors,
            'routeType' => $this->routeType,
        ];

        $totalResults = count($coAuthors);
        dump($totalResults);
        // If empty result
        if ($totalResults == 0) {
            return view('co_authors.index')->with($data);
        }

        if ($currentPage > 1) { // at page 2,3...
            $previousPage = $url . ($currentPage - 1);

            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);

                return view('co_authors.index')->with(array_merge($data, compact('previousPage', 'nextPage')));
            }

            return view('co_authors.index')->with(array_merge($data, compact('previousPage')));
        } else { // at page 1
            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);
            }

            return view('co_authors.index')->with(array_merge($data, compact('nextPage')));
        }
    }
}
