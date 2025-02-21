<?php

namespace Hichamagm\IzAgentShared\Services;

use Hichamagm\IzAgentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzAgentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;

class ApiModelBase extends ApiService
{
    public static function api(){
        return new self();
    }

    public function getMany(array $params = [])
    {
        return Http::withHeaders($this->headers)->get($this->baseUrl, $params);
    }
    
    public function getOne($id, array $params = [])
    {
        return Http::withHeaders($this->headers)->get("{$this->baseUrl}/$id", $params);
    }

    public function postOne(array $data = [])
    {
        return Http::withHeaders($this->headers)->post($this->baseUrl, $data);
    }

    public function updateOne($id, array $data = [])
    {
        return Http::withHeaders($this->headers)->patch("{$this->baseUrl}/$id", $data);
    }

    public function destroyOne($id, array $data = [])
    {
        return Http::withHeaders($this->headers)->delete("{$this->baseUrl}/$id", $data);
    }

    public function validateResourceExistence($id, $shouldExist = true)
    {
        return new ValidateServiceResourceExistence(
            $this->getOne($id),
            $this->name,
            $shouldExist
        );
    }

    public function validateCriteriaExistence($searchCriteria, $shouldExist = true)
    {
        return new ValidateServiceCriteriaExistence(
            $this->getMany($searchCriteria),
            $this->name,
            $shouldExist
        );
    }
}