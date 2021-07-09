<?php

namespace App\Helpers;


use App\Models\User;

class UserMgtHelper {

    //Get school Id
    public static function getUserId()
    {
        $userId = \Auth::user()->id;
        return $userId;
    }

}

