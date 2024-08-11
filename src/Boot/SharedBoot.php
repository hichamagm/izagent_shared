<?
namespace Hichamagm\IzagentShared\Boot;

use Hichamagm\IzagentShared\Guards\UserSessionGuard;
use Illuminate\Support\Facades\Auth;

class SharedBoot {

    static function AuthBoot()
    {
        Auth::extend('custom', function ($app, $name, array $config) {
            return new UserSessionGuard($app['session.store']);
        });
    }

    static function AppBoot()
    {
        //
    }
}