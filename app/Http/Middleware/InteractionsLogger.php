<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class InteractionsLogger
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Log the request details
        Log::channel('daily')->info('Service interaction:', [
            'user_id' => optional(auth()->user())->id,
            'url' => $request->fullUrl(),
            'request_data' => $request->all(),
            'request_http_method' => $request->method(),
            'response_http_code' => $response->getStatusCode(),
            'response_data' => json_decode($response->getContent(), true),
            'client_ip' => $request->ip()
        ]);

        return $response;
    }
}
