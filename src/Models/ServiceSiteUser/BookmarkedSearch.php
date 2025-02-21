<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\ApiService;

class BookmarkedSearch extends ApiService
{
    public $name = "Bookmarked Search";

    public function __construct()
    {
        $this->baseUrl = "http://service_site_user:80/api/bookmarked_searches";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }
}