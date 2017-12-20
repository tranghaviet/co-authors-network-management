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

    public static function array_push_if_not_exist(&$array, $value)
    {
        if (! in_array($value, $array)) {
            array_push($array, $value);
        }
    }
}
