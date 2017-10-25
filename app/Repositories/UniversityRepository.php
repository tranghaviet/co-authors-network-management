<?php

namespace App\Repositories;

use App\Models\University;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UniversityRepository.
 *
 * @version October 11, 2017, 1:54 pm ICT
 *
 * @method University findWithoutFail($id, $columns = ['*'])
 * @method University find($id, $columns = ['*'])
 * @method University first($columns = ['*'])
 */
class UniversityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'city_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return University::class;
    }
}
