<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\University;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Repositories\UniversityRepository;
use App\Http\Requests\UpdateUniversityRequest;
use Prettus\Repository\Criteria\RequestCriteria;

class UniversityController extends AppBaseController
{
    /** @var  UniversityRepository */
    private $universityRepository;
    private $routeType = '';

    public function __construct(UniversityRepository $universityRepo, Request $request)
    {
        $this->universityRepository = $universityRepo;
        $this->routeType = $request->is('admin/*') ? '':'user.';
    }

    /**
     * Display a listing of the University.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->universityRepository->pushCriteria(new RequestCriteria($request));
        $universities = $this->universityRepository
            ->with('city')
            ->paginate(config('constants.DEFAULT_PAGINATION'));

        extract(get_object_vars($this));

        return view('universities.index', compact('universities', 'routeType'));
    }

    /**
     * Display the specified University.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $university = $this->universityRepository->findWithoutFail($id);

        if (empty($university)) {
            Flash::error('University not found');

            return redirect(route('universities.index'));
        }

        extract(get_object_vars($this));

        return view('universities.show', compact('university', 'routeType'));
    }

    /**
     * Show the form for editing the specified University.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $university = $this->universityRepository->findWithoutFail($id);

        if (empty($university)) {
            Flash::error('University not found');

            return redirect(route('universities.index'));
        }

        return view('universities.edit')->with('university', $university);
    }

    /**
     * Update the specified University in storage.
     *
     * @param  int $id
     * @param UpdateUniversityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUniversityRequest $request)
    {
        $university = $this->universityRepository->findWithoutFail($id);

        if (empty($university)) {
            Flash::error('University not found');

            return redirect(route('universities.index'));
        }

        $university = $this->universityRepository->update($request->all(), $id);

        Flash::success('University updated successfully.');

        return redirect(route('universities.index'));
    }

    /**
     * Remove the specified University from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $university = $this->universityRepository->findWithoutFail($id);

        if (empty($university)) {
            Flash::error('University not found');

            return redirect(route('universities.index'));
        }

        $this->universityRepository->delete($id);

        Flash::success('University deleted successfully.');

        return redirect(route('universities.index'));
    }

    public function search(SearchRequest $request)
    {
        $universities = $this->universityRepository->search($request->q)
            ->paginate(config('constants.DEFAULT_PAGINATION', 15));

        $universities = University::search($request->q)->paginate(12);

        return view('universities.index', compact('universities'));
    }
}
