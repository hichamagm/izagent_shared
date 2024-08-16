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
     * @param string|null $model
     * @param bool $collection
     * @param bool $paginated
     * @return mixed
     */
    protected function sendRequest(Response $response, ?string $model = null, bool $collection = false, bool $paginated = false)
    {
        if ($response->successful()) {
            return $this->mapResponse($response, $model, $collection, $paginated);
        }

        $this->logError($response);

        return null;
    }

    /**
     * Maps the response to the appropriate model or returns the raw data.
     *
     * @param Response $response
     * @param string|null $model
     * @param bool $collection
     * @param bool $paginated
     * @return mixed
     */
    protected function mapResponse(Response $response, ?string $model, bool $collection, bool $paginated)
    {
        $json = $response->json();

        if ($model) {
            if ($paginated && $collection) {
                return (object) array_merge($json, [
                    "data" => $model::fromCollection($json["data"])
                ]);
            }

            return $collection ? $model::fromCollection($json) : $model::fromArray($json);
        }

        return $json;
    }

    /**
     * Logs errors from the response.
     *
     * @param Response $response
     * @return void
     */
    protected function logError(Response $response)
    {
        Log::error('API request failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }
}