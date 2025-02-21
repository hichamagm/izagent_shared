<?php

namespace Hichamagm\IzagentShared\Models;

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
    protected function handleResponse(Response $response, ?string $model = null, bool $collection = false, bool $paginated = false)
    {
        return $this->mapResponse($response, $model, $collection, $paginated);
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

        if ($model && $response->successful()) {
            if ($paginated && $collection) {
                return (object) array_merge($json, [
                    "data" => $model::fromCollection($json["data"])
                ]);
            }

            return $collection ? $model::fromCollection($json) : (object) $json;
        }

        return (object) $json;
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