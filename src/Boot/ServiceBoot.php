<?php

namespace Hichamagm\IzagentShared\Boot;

use Hichamagm\IzagentShared\Guards\CustomGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ServiceBoot {

    static function AuthBoot()
    {
        Auth::extend('shared-guard', function ($app, $name, array $config) {
            return new CustomGuard($app->make(Request::class));
        });
    }

    static function AppBoot()
    {
        JsonResource::withoutWrapping();
    }
}