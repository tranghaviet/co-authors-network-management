<?php

namespace App\Repositories;

use App\Models\AuthorPaper;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AuthorPaperRepository.
 * @version October 8, 2017, 8:47 pm ICT
 *
 * @method AuthorPaper findWithoutFail($id, $columns = ['*'])
 * @method AuthorPaper find($id, $columns = ['*'])
 * @method AuthorPaper first($columns = ['*'])
 */
class AuthorPaperRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'author_id',
        'paper_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return AuthorPaper::class;
    }
}
