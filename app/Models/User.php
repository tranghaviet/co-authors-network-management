<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 * @package App\Models
 * @version October 8, 2017, 5:28 pm ICT
 *
 * @property string name
 * @property string email
 * @property string password
 * @property string gender
 * @property string phone
 * @property string remember_token
 */
class User extends Authenticatable
{
    use Notifiable;

    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'phone',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'gender' => 'string',
        'phone' => 'string',
        'remember_token' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'password' => 'required|string|min:6',
    ];
}
