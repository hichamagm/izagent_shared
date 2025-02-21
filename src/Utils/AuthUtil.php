<?php

namespace Hichamagm\IzagentShared\Utils;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthUtil {

    public static function isUserType($type)
    {
        $user = Auth::user();

        if($user == null || $type !== $user->type){
            return false;
        }

        return true;
    }
}