<?php
namespace Hichamagm\IzagentShared\Services\EmailCampaigns;

use Illuminate\Support\Facades\Http;

class Analytics {

    protected $serviceApi = 'http://localhost/email_campaigns/postmark/analytics';

    public function postAnalytics($load){
        $response =  Http::post("$this->serviceApi", $load);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json($response->json(), 422);
        }
    }
}