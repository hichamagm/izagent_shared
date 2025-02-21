<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\ApiService;

class Search extends ApiService
{
    public $name = "Search";

    public function __construct()
    {
        $this->baseUrl = "http://service_site_user:80/api/searches";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }

    public function getOne($id, array $data = [])
    {
        return $this->fallbackResponse();
    }

    public function updateOne($id, array $data = []){
        return $this->fallbackResponse();
    }

    public function destroyOne($id, array $data = []){
        return $this->fallbackResponse();
    }
}