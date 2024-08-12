<?php

namespace Hichamagm\IzagentShared\Services\WebGatewayService;

use Hichamagm\IzagentShared\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Hichamagm\IzagentShared\Models\Domain;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;

class DomainClient extends BaseService
{
    protected $baseUrl = "http://localhost/web_gateway_service/domains";
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

    public function validateResourceExistence($id, $shouldExist)
    {
        return new ValidateServiceResourceExistence(
            fn() => $this->getOne($id),
            "Domain",
            $shouldExist
        );
    }

    public function validateCriteriaExistence($searchCriteria, $shouldExist)
    {
        return new ValidateServiceCriteriaExistence(
            fn() => $this->getMany($searchCriteria),
            "Domain",
            $shouldExist
        );
    }
}