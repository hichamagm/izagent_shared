<?php

namespace Hichamagm\IzagentShared\Models\ServiceMls;

use Hichamagm\IzagentShared\Models\BaseService;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MlsListing extends BaseService
{
    protected $headers = ["Accept" => "application/json"];
    protected $baseUrl = "http://service_mls:80/api/listings";

    public function getOne($id, array $queryParams = [])
    {
        return Http::withHeaders($this->headers)->get("{$this->baseUrl}/$id", $queryParams);
    }

    public function getMany(array $queryParams = [])
    {
        return Http::withHeaders($this->headers)->get($this->baseUrl, $queryParams);
    }

    public function getManyForMap(array $queryParams = [])
    {
        return Http::withHeaders($this->headers)->get("{$this->baseUrl}/map", $queryParams);
    }

    public function getManyAddresses(string $query)
    {
        return Http::withHeaders($this->headers)->get("{$this->baseUrl}/address_autocomplete", ["query" => $query]);
    }

    public function validateResourceExistence($id, $shouldExist)
    {
        return new ValidateServiceResourceExistence(
            $this->getOne($id),
            "MLS Listing",
            $shouldExist
        );
    }

    public function validateCriteriaExistence($searchCriteria, $shouldExist)
    {
        return new ValidateServiceCriteriaExistence(
            $this->getMany($searchCriteria),
            "MLS Listing",
            $shouldExist
        );
    }
}