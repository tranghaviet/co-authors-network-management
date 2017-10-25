<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserRepository.
 *
 * @version October 8, 2017, 5:28 pm ICT
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
 */
class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'gender',
        'phone',
        'remember_token',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return User::class;
    }
}
