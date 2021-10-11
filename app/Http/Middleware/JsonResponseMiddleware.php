<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if (! $response instanceof JsonResponse) {
            $response = new JsonResponse(
                $response->getContent(),
                $response->getStatusCode()
            );
        }

        return $response;
    }
}
