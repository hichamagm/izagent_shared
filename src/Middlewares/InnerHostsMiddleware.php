<?php

namespace Hichamagm\IzagentShared\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class InnerHostsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appAccessToken = $request->header("X-App-Access-Token");

        if($appAccessToken && $appAccessToken == env("APP_ACCESS_TOKEN")){
            return $next($request);
        }
        
        return response()->json(["message" => "Unauthorized host"], 401);
    }
}