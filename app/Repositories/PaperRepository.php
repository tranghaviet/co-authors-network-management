<?php

namespace App\Repositories;

use App\Models\Paper;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PaperRepository.
 * @version October 8, 2017, 8:05 pm ICT
 *
 * @method Paper findWithoutFail($id, $columns = ['*'])
 * @method Paper find($id, $columns = ['*'])
 * @method Paper first($columns = ['*'])
 */
class PaperRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'cover_date',
        'abstract',
        'url',
        'issn',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Paper::class;
    }
}
