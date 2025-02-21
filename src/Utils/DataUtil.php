<?php

namespace Hichamagm\IzagentShared\Utils;

use Hichamagm\IbeautyfiShared\Models\Domain;
use Illuminate\Support\Str;

class DataUtil {

    public static function snakeKeys($data)
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $newKey = Str::snake($key);
                $result[$newKey] = self::snakeKeys($value);
            }
            return $result;
        } else {
            return $data;
        }
    }

    public static function camelKeys($data){
        if (is_array($data)) {
            $result = [];
    
            foreach ($data as $key => $value) {
                $newKey = Str::camel($key);
                $result[$newKey] = self::camelKeys($value);
            }
    
            return $result;
            
        } else {
            return $data;
        }
    }
}