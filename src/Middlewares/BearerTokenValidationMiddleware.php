<?php

namespace Hichamagm\IzagentShared\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BearerTokenValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptedHosts = [
            "service_web_router",
            "service_contacts",
            "service_site_builder",
            "service_site_frontend",
            "service_site_user",
            "service_site_analytics",
            "service_email_campaigns",
            "service_auth",
            "service_listings",
            "service_media",
            "service_mls"
        ];

        if(in_array($request->getHost(), $acceptedHosts) && $request->getPort() == 80){
            //Deprecated
            app()->bind('access', fn() => "full");
            app()->bind('user', fn() => []);

            //New
            
            if($request->header('X-User-Id')){
                session(['user_id' => $request->header('X-User-Id')]);
                return $next($request);
            }

            return response()->json(["message" => "Unauthorized"], 401);
        }

        app()->bind('access', fn() => "limited");

        // Extract the Bearer token from the request
        $token = $request->bearerToken();

        if(!$token){
            return response()->json(["message" => "Unauthorized"], 401);
        }

        // Replace 'your-auth-endpoint' with the actual authentication endpoint
        $authEndpoint = 'http://service_auth:80/validate_token';

        // Make a request to the authentication endpoint
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
            ->get($authEndpoint);

        // Check if the token is valid
        if ($response->successful()) {
            $data = $response->json();
            $user = (object) $data['user'];

            app()->bind('user', function() use($user){
                return $user;
            });

            Log::info($user->id);

            session(['user_id' => $user->id]);

            return $next($request);
        } else {
            // Token is not valid, return an unauthorized response
            return response()->json($response->body(), 401);
        }
        
    }
}
