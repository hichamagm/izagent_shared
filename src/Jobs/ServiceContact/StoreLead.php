<?php

namespace Hichamagm\IzagentShared\Jobs\ServiceContact;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $targetClass = "App\\Jobs\\StoreLead";
    public $serviceName = "service_contacts";

    public function __construct(public $lead, public $userId = null, public $contributers = null)
    {
        $this->onConnection("redis");
        $this->onQueue("contacts_listeners");
    }

    public function handle()
    {
        // Job is handled in the corresponding service
    }
}