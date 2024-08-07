<?php
namespace Hichamagm\IzagentShared\Services\Account;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailConnectionService {

    protected $serviceApi = 'http://localhost/account/email_connections';

    public $emailConnection = [];

    public function getEmailConnections(array $params = []){
        $response = Http::get($this->serviceApi, $params);

        if ($response->successful()) {
            return $response->json();
        } else {
            return [];
        }
    }

    public function getEmailConnection(int $id, array $params = []){
        $response = Http::get("$this->serviceApi/$id", $params);
        return new EmailConnectionModel($response->object());
    }
}