<?php

namespace Hichamagm\IzagentShared\Services\Account;

use Illuminate\Support\Facades\Log;

class EmailConnectionModel {

    public $email;

    public function __construct($model)
    {
        $this->email = optional($model);
    }

    public function getConnection()
    {
        return $this->email->connection;
    }

    public function isActive()
    {
        return $this->email->status == 'Active';
    }

    public function isVerified()
    {
        return $this->email->connection->verified;
    }
}