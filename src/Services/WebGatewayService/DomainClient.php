<?php

namespace Hichamagm\IzagentShared\Services\WebGatewayService;

use Hichamagm\IzagentShared\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Hichamagm\IzagentShared\Models\Domain;

class DomainClient extends BaseService
{
    protected $baseUrl = "http://localhost/web_gateway_service";

    public function getOne($domainId)
    {
        return $this->sendRequest(
            fn() => Http::get("{$this->baseUrl}/domains/{$domainId}"),
            Domain::class
        );
    }

    public function getMany(array $queryParams = [])
    {
        return $this->sendRequest(
            fn() => Http::get("{$this->baseUrl}/domains/", $queryParams),
            Domain::class
        );
    }

    public function postOne(array $domainData)
    {
        return $this->sendRequest(
            fn() => Http::post("{$this->baseUrl}/domains", $domainData),
            Domain::class
        );
    }

    public function deleteOne($domainId)
    {
        return $this->sendRequest(
            fn() => Http::delete("{$this->baseUrl}/domains/{$domainId}")
        );
    }
}