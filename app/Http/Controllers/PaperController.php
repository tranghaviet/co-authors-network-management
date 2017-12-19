<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SearchRequest;
use App\Repositories\PaperRepository;
use App\Http\Requests\UpdatePaperRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\Process\Process as Process;

class PaperController extends AppBaseController
{
    /** @var  PaperRepository */
    private $paperRepository;

    private $routeType = '';

    public function __construct(PaperRepository $paperRepo, Request $request)
    {
        $this->routeType = $request->is('admin/*') ? '' : 'user.';

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
//        return SearchHelper::searchPaper($request, $this->routeType);

        $query = trim($request->q);

        if (! strlen($query)) {
            \Flash::warning('Please enter search keyword.');

            return redirect()->back();
        }

        $currentPage = intval($request->page);

        if (empty($currentPage)) {
            $currentPage = 1;
        }

        if (! is_numeric($currentPage) || $currentPage < 1) {
            Flash::error('Invalid page.');

            return view('papers.index')->with([
                'routeType' => $this->routeType,
            ]);
        }

        $perPage = config('constants.DEFAULT_PAGINATION');
        $offset = $perPage * ($currentPage - 1);

        if (! $request->session()->has('paper_search_' . $query . '_' . strval($currentPage))) {
            $execution = "select *, match(papers.title) against ('{$query}') as s1
                          from papers
                          where 
                            match(papers.title) against ('{$query}')
                            or papers.title like '%${query}%'
                            or papers.id like '%{$query}%'
                            or papers.issn like '%{$query}%'
                          order by s1 desc limit {$perPage} offset {$offset};";

            try {
                $papers = DB::select($execution);
            } catch (\Exception $e) {
                try {
                    \Artisan::call('paper:re-index');
                    $papers = DB::select($execution);
                } catch (\Exception $e) {
                    \Log::debug('paper:re-index fail', $e->getTrace());
                    \Flash::warning('Index in progress...try after few seconds');
                    $process = new Process('php ../artisan paper:re-index');
                    $process->start();

                    return redirect()->back();
                }
            }

            session(['author_search_' . $query . '_' . strval($currentPage) => $papers]);
        } else {
            $papers = $request->session()->get('paper_search_' . $query . '_' . strval($currentPage));
        }

        $totalResults = count($papers);
        $papers = json_decode(json_encode($papers), true);

        // Pagination
        $data = [
            'papers' => $papers,
            'routeType' => $this->routeType,
        ];

        // If empty result
        if ($totalResults == 0) {
            return view('papers.index')->with($data);
        }

        $url = route($this->routeType.'papers.search') . '?q=' . $query . '&page=';

        if ($currentPage > 1) { // at page 2,3...
            $previousPage = $url . ($currentPage - 1);

            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);

                return view('papers.index')->with(array_merge($data, compact('previousPage', 'nextPage')));
            }

            return view('papers.index')->with(array_merge($data, compact('previousPage')));
        } else { // at page 1
            if ($totalResults == 15) {
                $nextPage = $url . ($currentPage + 1);
            }

            return view('papers.index')->with(array_merge($data, compact('nextPage')));
        }
    }
}
