<?php

namespace Hichamagm\IzagentShared\CustomDataEndpoints\ServiceMls;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Analytics {

    protected static $headers = ["Accept" => "application/json"];
    protected static $baseUrl = "http://service_mls:80/api/analytics";

    public static function citiesList(array $queryParams = [])
    {
        return Http::withHeaders(self::$headers)->get(self::$baseUrl . "/cities_list", $queryParams);
    }
}