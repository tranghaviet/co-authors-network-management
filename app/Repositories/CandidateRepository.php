<?php

namespace App\Repositories;

use App\Models\Candidate;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CandidateRepository.
 *
 * @version October 8, 2017, 9:45 pm ICT
 *
 * @method Candidate findWithoutFail($id, $columns = ['*'])
 * @method Candidate find($id, $columns = ['*'])
 * @method Candidate first($columns = ['*'])
 */
class CandidateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'co_author_id',
        'no_of_mutual_authors',
        'no_of_joint_papers',
        'no_of_joint_subjects',
        'no_of_joint_keywords',
        'score_1',
        'score_2',
        'score_3',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Candidate::class;
    }
}
