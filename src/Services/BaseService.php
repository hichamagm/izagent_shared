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
    public function sendRequest(\Closure $callback, string $model = null)
    {
        $response = $callback();

        if ($response->successful()) {
            if($model){
                return $model::fromArray($response->json());
            }

            return $response->json();
        }

        return null;
    }
}