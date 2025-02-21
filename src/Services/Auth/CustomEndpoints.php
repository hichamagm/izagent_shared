<?php

namespace Hichamagm\IzAgentShared\Services\Auth;

use Hichamagm\IzAgentShared\Services\ApiService;
use Illuminate\Support\Facades\Http;

class CustomEndpoints extends ApiService {

    public $host = "service_auth";

    public function user($userId)
    {
        $this->withUser($userId);
        return Http::withHeaders($this->headers)->get("$this->baseUrl/user");
    }

    public function userByToken($token)
    {
        $this->withBearerToken($token);
        return Http::withHeaders($this->headers)->get("$this->baseUrl/user");
    }

    public function login($creds)
    {
        return Http::withHeaders($this->headers)->post("$this->baseUrl/login", $creds);
    }
}