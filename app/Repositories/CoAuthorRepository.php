<?php

namespace App\Repositories;

use App\Models\CoAuthor;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CoAuthorRepository
 * @package App\Repositories
 * @version October 8, 2017, 9:23 pm ICT
 *
 * @method CoAuthor findWithoutFail($id, $columns = ['*'])
 * @method CoAuthor find($id, $columns = ['*'])
 * @method CoAuthor first($columns = ['*'])
*/
class CoAuthorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_author_id',
        'second_author_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CoAuthor::class;
    }
}
