<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugCsrfMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log CSRF token information
        Log::info('CSRF Debug', [
            'url' => $request->url(),
            'method' => $request->method(),
            'has_csrf_token' => $request->hasHeader('X-CSRF-TOKEN'),
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'session_status' => session()->isStarted(),
        ]);

        return $next($request);
    }
}
