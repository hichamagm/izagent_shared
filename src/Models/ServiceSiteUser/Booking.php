<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\ApiService;

class Booking extends ApiService
{
    public $name = "Booking";

    public function __construct()
    {
        $this->baseUrl = "http://service_site_user:80/api/bookings";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }

    public function updateOne($id, array $data = []){
        return $this->fallbackResponse();
    }

    public function destroyOne($id, array $data = []){
        return $this->fallbackResponse();
    }
}