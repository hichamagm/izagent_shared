<?php

namespace Hichamagm\IzagentShared\Models\ServiceWebRouter;

use Hichamagm\IzagentShared\Models\ApiService;

class Domain extends ApiService
{
    public $name = "Domain";

    public function __construct()
    {
        $this->baseUrl = "http://service_web_router:80/api/domains";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }
}