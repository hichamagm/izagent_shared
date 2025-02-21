<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\BaseService;
use Hichamagm\IzagentShared\Validation\ValidateServiceCriteriaExistence;
use Hichamagm\IzagentShared\Validation\ValidateServiceResourceExistence;
use Illuminate\Support\Facades\Http;

class User extends BaseService
{
    public $headers = ["Accept" => "application/json"];
    protected $baseUrl = "http://service_site_user:80/api/users";

    public function __construct()
    {
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }

    public static function fromCollection(array $items)
    {
        return array_map(fn($item) => new self($item), $items);
    }

    public static function forSiteUser($id)
    {
        $instance = new self();
        $instance->headers = array_merge($instance->headers, ["X-User-Id" => $id]);

        return $instance;
    }

    public static function forPlatformUser($id)
    {
        $instance = new self();
        $instance->headers = array_merge($instance->headers, ["X-Platform-User-Id" => $id]);

        return $instance;
    }

    public function getOne($domainId)
    {
        return Http::withHeaders($this->headers)->get("{$this->baseUrl}/$domainId");
    }

    public function getMany(array $queryParams = [])
    {
        return $this->handleResponse(
            Http::withHeaders($this->headers)->get($this->baseUrl, $queryParams),
            self::class,
            true,
            true
        );
    }

    public function postOne(array $domainData)
    {
        return Http::withHeaders($this->headers)->post($this->baseUrl, $domainData);
    }

    public function updateOne($id, array $domainData)
    {
        return Http::withHeaders($this->headers)->patch("{$this->baseUrl}/$id", $domainData);
    }

    public function deleteOne($domainId)
    {
        return $this->handleResponse(
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

    public function validateCredentials($email, $password, $websiteId)
    {
        return Http::withHeaders($this->headers)->post("{$this->baseUrl}/validate_credentials", [
            "websiteId" => $websiteId,
            "email" => $email,
            "password" => $password
        ]);
    }
}