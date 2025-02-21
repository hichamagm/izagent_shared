<?php

namespace Hichamagm\IzAgentShared\Services;

use Illuminate\Support\Facades\Log;

class ApiService
{
    public $name;
    public $baseUrl;
    public $host;
    public $method;
    public $headers = ['Content-Type' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'];

    public function __construct()
    {
        $this->setBaseUrl();
        $this->addHeaders(["X-Forwarded-Host" => $this->host]);
        $this->withToken();
    }

    public function setBaseUrl()
    {   
        if($this->host){
            $this->baseUrl = "http://" . $this->host;

            if($this->method){
                $this->baseUrl .= "/$this->method";
            }
        }else{
            Log::warning("Host $this->host is not set");
        }
    }
    
    public function withToken()
    {
        $this->addHeaders([ "X-App-Access-Token" => env("APP_ACCESS_TOKEN")]);
    }

    public function withBearerToken($token)
    {
        $this->addHeaders(["Authorization" => "Bearer $token"]);
    }

    public function withUser(int $userId)
    {
        $this->addHeaders(["X-User-Id" => $userId]);
    }

    public function addHeaders(array $array)
    {
        $this->headers = [...$this->headers, ...$array];
    }

    public function forUser($id)
    {
        $this->addHeaders(["X-User-Id" => $id]);
    }

    public function fallbackResponse()
    {
        return response()->json([
            "message" => "Route is not found" 
        ], 404);
    }
}