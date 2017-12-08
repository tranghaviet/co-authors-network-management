<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCountryRequest;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CountryController extends AppBaseController
{
    /** @var  CountryRepository */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepo)
    {
        $this->countryRepository = $countryRepo;
    }

    /**
     * Store a newly created Country in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $country = $this->countryRepository->create($input);

        Flash::success('Country saved successfully.');

        return redirect(route('countries.index'));
    }

    /**
     * Display the specified Country.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            Flash::error('Country not found');

            return redirect(route('countries.index'));
        }

        return view('countries.show')->with('country', $country);
    }

    /**
     * Show the form for editing the specified Country.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            Flash::error('Country not found');

            return redirect(route('countries.index'));
        }

        return view('countries.edit')->with('country', $country);
    }

    /**
     * Update the specified Country in storage.
     *
     * @param  int              $id
     * @param UpdateCountryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCountryRequest $request)
    {
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            Flash::error('Country not found');

            return redirect(route('countries.index'));
        }

        $country = $this->countryRepository->update($request->all(), $id);

        Flash::success('Country updated successfully.');

        return redirect(route('countries.index'));
    }

    /**
     * Remove the specified Country from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $country = $this->countryRepository->findWithoutFail($id);

        if (empty($country)) {
            Flash::error('Country not found');

            return redirect(route('countries.index'));
        }

        $this->countryRepository->delete($id);

        Flash::success('Country deleted successfully.');

        return redirect(route('countries.index'));
    }
}
