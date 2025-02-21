<?php

namespace Hichamagm\IzagentShared\Models\ServiceAccount;

use Hichamagm\IzagentShared\Models\ApiService;

class EmailConnection extends ApiService
{
    public $name = "Email Connection";

    public function __construct()
    {
        $this->baseUrl = "http://service_account:80/api/email_connections";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }
}