<?php

namespace Hichamagm\IzagentShared\Services\WebGatewayService;

use Hichamagm\IzagentShared\Services\BaseService;
use Illuminate\Support\Facades\Http;
use Hichamagm\IzagentShared\Models\Domain;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Log;

class DomainClient extends BaseService
{
    protected $baseUrl = "http://localhost/service_web_gateway/api/domains";
    protected $headers = [];

    public function __construct($userId)
    {
        $this->headers = [
            "X-User-Id" => $userId,
            'Accept' => 'application/json',
        ];
    }

    public function getOne($domainId)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->get("$this->baseUrl/$domainId"),
            Domain::class
        );
    }

    public function getMany(array $queryParams = [])
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->get("$this->baseUrl", $queryParams),
            Domain::class,
            true,
            true
        );
    }

    public function postOne(array $domainData)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->post("$this->baseUrl", $domainData),
            Domain::class
        );
    }

    public function deleteOne($domainId)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->delete("$this->baseUrl/$domainId")
        );
    }

    public function validateResourceExistence($id, $shouldExist)
    {
        return new ValidateServiceResourceExistence(
            $this->getOne($id),
            "Domain",
            $shouldExist
        );
    }

    public function validateCriteriaExistence($searchCriteria, $shouldExist)
    {
        return new ValidateServiceCriteriaExistence(
            $this->getMany($searchCriteria),
            "Domain",
            $shouldExist
        );
    }
}