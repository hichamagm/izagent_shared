<?php

namespace Hichamagm\IzagentShared\Services\WebGatewayService;

use Hichamagm\IzagentShared\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Hichamagm\IzagentShared\Models\Domain;

class DomainClient extends BaseService
{
    protected $baseUrl = "http://localhost/web_gateway_service";
    protected $headers = [];

    public function __construct($userId)
    {
        $headers["x-user_id"] = $userId;
    }

    public function getOne($domainId)
    {
        return $this->sendRequest(
            fn() => Http::withHeaders($this->headers)->get("{$this->baseUrl}/domains/{$domainId}"),
            Domain::class
        );
    }

    public function getMany(array $queryParams = [])
    {
        return $this->sendRequest(
            fn() => Http::withHeaders($this->headers)->get("{$this->baseUrl}/domains/", $queryParams),
            Domain::class
        );
    }

    public function postOne(array $domainData)
    {
        return $this->sendRequest(
            fn() => Http::withHeaders($this->headers)->post("{$this->baseUrl}/domains", $domainData),
            Domain::class
        );
    }

    public function deleteOne($domainId)
    {
        return $this->sendRequest(
            fn() => Http::withHeaders($this->headers)->delete("{$this->baseUrl}/domains/{$domainId}")
        );
    }
}