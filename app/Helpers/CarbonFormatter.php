<?php

namespace App\Helpers;

use Carbon\Carbon;

class CarbonFormatter
{
    public static function getCarbonFromString($string)
    {
        $result = null;

        if($string && $string != '1970-01-01 00:00:00'){
            $result = Carbon::createFromTimestamp(strtotime($string));
        }

        return $result;
    }
}