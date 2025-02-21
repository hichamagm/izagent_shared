<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\ApiService;

class Inquiry extends ApiService
{
    public $name = "Inquiry";

    public function __construct()
    {
        $this->baseUrl = "http://service_site_user:80/api/inquiries";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }

    public function getMany(array $data = []){
        return $this->fallbackResponse();
    }

    public function updateOne($id, array $data = []){
        return $this->fallbackResponse();
    }

    public function destroyOne($id, array $data = []){
        return $this->fallbackResponse();
    }
}