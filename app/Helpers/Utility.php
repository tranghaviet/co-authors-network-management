<?php

namespace App\Helpers;

use Auth;
use Request;

class Utility
{
    public static function displayForAdmin()
    {
        return ! Auth::guest() && Request::is('admin/*');
    }
}
