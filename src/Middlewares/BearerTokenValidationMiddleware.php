<?php

namespace Hichamagm\IzagentShared\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class BearerTokenValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $fullAccessIps = [
            "127.0.0.1"
        ];

        if(in_array($request->ip(), $fullAccessIps)){
            app()->bind('access', fn() => "full");
            app()->bind('user', fn() => []);

            return $next($request);
        }

        app()->bind('access', fn() => "limited");

        // Extract the Bearer token from the request
        $token = $request->bearerToken();

        // Replace 'your-auth-endpoint' with the actual authentication endpoint
        $authEndpoint = 'http://localhost/auth/validate_token';

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

            return $next($request);
        } else {
            // Token is not valid, return an unauthorized response
            return response()->json($response->body(), 401);
        }
        
    }
}
