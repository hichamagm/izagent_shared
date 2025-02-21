<?php

namespace Hichamagm\IzagentShared\Jobs\ServiceAuth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $targetClass = "App\\Jobs\\StoreUser";
    public $serviceName = "service_auth";

    public function __construct(public array $user)
    {
        $this->onConnection("redis");
        $this->onQueue("manage_users");
    }

    public function handle()
    {
        // Job is handled in the corresponding service
    }
}