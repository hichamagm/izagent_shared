<?php

namespace Hichamagm\IzagentShared\Models;

use Hichamagm\IzagentShared\Services\BaseService;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Domain extends BaseService
{
    public $id;
    public $name;
    public $status;
    public $serviceName;
    public $type;
    public $nextFetch;
    public $errorMsg;
    public $sslEnabled;
    public $createdAt;
    public $updatedAt;

    protected $headers = ["Accept" => "application/json"];
    protected $baseUrl = "http://localhost/service_web_gateway/api/domains";

    public function __construct(array $attributes = [])
    {
        $this->fillAttributes($attributes);
    }

    /**
     * Fills the model attributes from an array.
     *
     * @param array $attributes
     * @return void
     */
    protected function fillAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{Str::camel($key)} = $value;
        }
    }

    public static function forUser($id)
    {
        $instance = new self();
        $instance->headers = array_merge($instance->headers, ["X-User-Id" => $id]);
        return $instance;
    }

    public static function fromArray(array $attributes)
    {
        return new self($attributes);
    }

    public static function fromCollection(array $items)
    {
        return array_map(fn($item) => new self($item), $items);
    }

    public function getOne($domainId)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->get("{$this->baseUrl}/$domainId"),
            self::class
        );
    }

    public function getMany(array $queryParams = [])
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->get($this->baseUrl, $queryParams),
            self::class,
            true,
            true
        );
    }

    public function postOne(array $domainData)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->post($this->baseUrl, $domainData),
            self::class
        );
    }

    public function deleteOne($domainId)
    {
        return $this->sendRequest(
            Http::withHeaders($this->headers)->delete("{$this->baseUrl}/$domainId")
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