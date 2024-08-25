<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Services\BaseService;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class User extends BaseService
{
    public $id;
    public $email;
    public $phoneNumber;
    public $createdAt;
    public $updatedAt;

    protected $headers = ["Accept" => "application/json"];
    protected $baseUrl = "http://service_site_site:80/api/users";

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
            "Site User",
            $shouldExist
        );
    }

    public function validateCriteriaExistence($searchCriteria, $shouldExist)
    {
        return new ValidateServiceCriteriaExistence(
            $this->getMany($searchCriteria),
            "Site User",
            $shouldExist
        );
    }
}