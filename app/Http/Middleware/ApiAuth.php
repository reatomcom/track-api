<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Client;
use App\Models\Device;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authToken = $request->header('Auth-Token', "undefined");

        if ($authToken == "undefined") {
            return response([
                "ok" => false,
                "error" => "Auth failed #403-u"
            ]);
        }

        if ($authToken == "") {
            return response([
                "ok" => false,
                "error" => "Auth failed #403-e"
            ]);
        }

        $device = Device::where('auth_token', $authToken)->first();
        if (!$device) {
            return response([
                "ok" => false,
                "error" => "Auth failed #403-1"
            ]);
        }

        $user = User::find($device->user_id);
        if (!$user) {
            return response([
                "ok" => false,
                "error" => "Auth failed #403-2"
            ]);
        }

        $client = Client::find($user->client_id);
        if (!$client) {
            return response([
                "ok" => false,
                "error" => "Auth failed #403-3"
            ]);
        } 

        $request->user = $user;
        $request->client = $client;
        $request->device = $device;

        return $next($request);
    }
}
