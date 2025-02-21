<?php

namespace Hichamagm\IzagentShared\Models;

use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;

class ApiService
{
    public $name;
    public $baseUrl;
    public $headers = ['Content-Type' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'];

    public function __construct()
    {
        $this->addHeaders(["X-Forwarded-Host" => gethostname()]);
    }

    public function addHeaders(array $array)
    {
        $this->headers = [...$this->headers, ...$array];
    }

    public function forUser($id)
    {
        $this->addHeaders(["X-User-Id" => $id]);
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

    public function fallbackResponse()
    {
        return response()->json([
            "message" => "Route is not found" 
        ], 404);
    }
}