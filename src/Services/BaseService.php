<?php

namespace Hichamagm\IzagentShared\Services;

class BaseService
{
    /**
     * Sends an HTTP request and maps the response to a given model.
     *
     * @param \Closure $callback
     * @param string $model
     * @return mixed
     */
    public function sendRequest(\Closure $callback, string $model = null, $collection = false, $paginated = false)
    {
        $response = $callback();

        if ($response->successful()) {
            $json = $response->json();

            if($model){
                if($paginated && $collection){
                    return collect([
                        ...$json,
                        "data" => $model::fromCollection($json["data"])
                    ]);
                }

                return $collection ? $model::fromCollection($json) :$model::fromArray($json);
            }

            return $json;
        }

        return null;
    }
}