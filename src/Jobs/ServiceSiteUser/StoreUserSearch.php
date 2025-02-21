<?php

namespace Hichamagm\IzagentShared\Jobs\ServiceSiteUser;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreUserSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $targetClass = "App\\Jobs\\StoreUserSearch";
    public $serviceName = "service_site_user";

    public function __construct(public array $search, public int $websiteId, public int $userId)
    {
        $this->onConnection("redis");
        $this->onQueue("site_user_listeners");
    }

    public function handle()
    {
        // Job is handled in the corresponding service
    }

}