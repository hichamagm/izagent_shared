<?php

namespace Hichamagm\IzagentShared\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class BaseService
{
    /**
     * Sends an HTTP request and maps the response to a given model.
     *
     * @param \Closure $callback
     * @param string $model
     * @return mixed
     */
    public function sendRequest(Response $response, string $model = null, $collection = false, $paginated = false)
    {
        if ($response->successful()) {
            $json = $response->json();

            if($model){
                if($paginated && $collection){
                    return (Object) [
                        ...$json,
                        "data" => $model::fromCollection($json["data"])
                    ];
                }

                return $collection ? $model::fromCollection($json) : $model::fromArray($json);
            }

            return $json;
        }

        return null;
    }
}