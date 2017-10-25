<?php

namespace App\Repositories;

use App\Models\Author;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AuthorRepository.
 *
 * @version October 8, 2017, 7:41 pm ICT
 *
 * @method Author findWithoutFail($id, $columns = ['*'])
 * @method Author find($id, $columns = ['*'])
 * @method Author first($columns = ['*'])
 */
class AuthorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'given_name',
        'surname',
        'email',
        'url',
        'university_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Author::class;
    }
}
