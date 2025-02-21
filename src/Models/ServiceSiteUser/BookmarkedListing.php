<?php

namespace Hichamagm\IzagentShared\Models\ServiceSiteUser;

use Hichamagm\IzagentShared\Models\ApiService;

class BookmarkedListing extends ApiService
{
    public $name = "Bookmarked Listing";

    public function __construct()
    {
        $this->baseUrl = "http://service_site_user:80/api/bookmarked_listings";
        $this->headers = [...$this->headers, "X-Forwarded-Host" => gethostname()];
    }

    public function updateOne($id, array $data = []){
        return $this->fallbackResponse();
    }
}