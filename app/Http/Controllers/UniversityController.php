<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUniversityRequest;
use App\Repositories\UniversityRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\University;

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
     * Show the form for creating a new University.
     *
     * @return Response
     */
    public function create()
    {
        return view('universities.create');
    }

    /**
     * Store a newly created University in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $university = $this->universityRepository->create($input);

        Flash::success('University saved successfully.');

        return redirect(route('universities.index'));
    }

    /**
     * Display the specified University.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $university = $this->universityRepository->findWithoutFail($id);

        if (empty($university)) {
            Flash::error('University not found');

            return redirect(route('universities.index'));
        }

        return view('universities.show')->with('university', $university);
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

    public function search(Request $request)
    {
        $universities = $this->universityRepository->search($request->q)->paginate(config('constants.DEFAULT_PAGINATION', 15));

        return view('universities.index', compact('universities'));
    }
}
