<?php

namespace Hichamagm\IzagentShared\Models;

use Hichamagm\IzagentShared\Services\BaseService;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MlsListing extends BaseService
{
    public $id;
    public $strtAddress;
    public $price;
    public $city;
    public $thumbnail;
    public $community;
    public $country;
    public $mlsId;
    public $category;
    public $createdAt;
    public $updatedAt;

    protected $headers = ["Accept" => "application/json"];
    protected $baseUrl = "http://localhost:8089/api/listings";

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